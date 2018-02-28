## API Testing Perch App
Laravel app with React frontend templating.
Uses 'axios' requests to api handles (defined in route/api.php) to yield data.

React components are located under resources/assets/js.
Instead of an index.html front page, we use 'welcome.blade.php' located under 
resources/views.

Current API calls:
- POST to /login (attempts a user login)
- POST to /register (attempts a new user registration)
- POST to /details (if signed in, retrieves user data)
- GET to /users (returns array of all User objects)
- GET to /students
- GET to /students/{student}
- POST to /students
- PUT to /students/{student}
- DELETE to /students/{student}