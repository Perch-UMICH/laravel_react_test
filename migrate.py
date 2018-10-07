import sys
import pyodbc # SQL Server
import mysql.connector # MySQL
import pandas as pd # Pandas -- can be refactored to not use pandas
import time

# something odd is going on with pyodbc and locale setting on mac 
# to run on macOS: env LANG=C python migrate.py

start_time = time.time()

## Constants
sourceTable = "sampleTable"

## SQL Credentials
sql_server = '' 
sql_database = '' 
sql_username = '' 
sql_password = ''

mysql_server = ""
mysql_database = ""
mysql_username = ""
mysql_password = ""
mysql_port = ""

## Connect to both DB
try:
    print ("Connecting to SQL Server")
    # may need to change the DRIVER depending on what's installed on your system
    SQL_conn = pyodbc.connect('DRIVER={ODBC Driver 17 for SQL Server};SERVER='+sql_server+';DATABASE='+sql_database+';UID='+sql_username+';PWD='+ sql_password)
except:
    sys.exit('failed to connect to Microsoft SQL database')

try:
    print ("Connecting to MySQL Server")
    MySQL_conn = mysql.connector.connect(user=mysql_username, password=mysql_password, host=mysql_server, port=mysql_port, database=mysql_database)
except mysql.connector.Error as err:
    print(err)
    sys.exit('failed to connect to MySQL database')

## Create cursors for both DB
msCursor = SQL_conn.cursor()
myCursor = MySQL_conn.cursor()

print ("Connected to both source and destination DBs.\n")

## Source table information
# Grab the columns from sourceTable
columns = pd.read_sql_query("SELECT * FROM syscolumns WHERE id = OBJECT_ID('%s')" % sourceTable, SQL_conn)
# Find data types
dtypes = pd.read_sql_query("SELECT * FROM systypes", SQL_conn)

## Migrate table into MySQL Server
# Drop MySQL Table if there is one
drop_stmt = "DROP TABLE IF EXISTS `%s`" % sourceTable
myCursor.execute(drop_stmt)

# Create the table on MySQL
create_stmt = "CREATE TABLE `%s` (" % sourceTable

# iterate through the column, generate create statement
for i, row in columns.iterrows():
    colType = dtypes[dtypes.xtype == row.xtype].iloc[0]['name']
    # varchar and int, just convert into sql
    # might need to add more data types that requires a specified length
    if colType == "varchar" or colType == "int":
        create_stmt += row['name'] + " " + colType + "(%s)" % row.length
    # everything else...
    else:
        if colType == "text":
            colType = "mediumtext"
        create_stmt += row['name'] + " " + colType
    
    # add commna and new line, except for last column
    if i != columns.shape[0] - 1:
        create_stmt += ",\n"

# closing the parenthesis
create_stmt += ")"
print ("Creating " + sourceTable + " on MySQL...", end=" ")
myCursor.execute(create_stmt)
print ("Done.\n")


## Migrate data
print ("Migrating data from " + sourceTable + " on SQL Server to MySQL...")
# Fetch first 1000 rows
msCursor.execute("select * from %s" % sourceTable)
data = msCursor.fetchmany(1000) # Microsoft SQL Server only fetches 1000 rows at once
# counter for stat
total = 0

while len(data) > 0:
    total += len(data)
    # populate MySQL db
    for row in data:
        # parse the row into a MySQL statement
        fieldList = ""
        for field in row:
            if field == None: 
                fieldList += "NULL,"
            else:
                fieldList += "'"+ str(field) + "',"

        # get rid of last column
        fieldList = fieldList[:-1]
        myCursor.execute("INSERT INTO " + sourceTable + " VALUES (" + fieldList + ")" )
    MySQL_conn.commit()
    if len(data) == 1000:
        print ("first 1000 rows inserted")
    data = msCursor.fetchmany(1000)

print ("Total of " + str(total) + " rows inserted")
print ("Migration Done.\n")

print ("Closing connections.\n")
SQL_conn.close()
MySQL_conn.close()

print("--- Operation took: %s seconds ---" % (time.time() - start_time))
