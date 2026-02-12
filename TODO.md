# TODO: Fix Signup Form and Backend for Render Deployment

## Tasks
- [x] Update signup.php: Change form action from "register.php" to "signup_process.php"
- [x] Update signup_process.php: Modify to handle form submission with redirects, add validation for confirm_password and terms, handle errors and success redirects
- [x] Fix HTTP 405 error in signup_process.php by changing from http_response_code(405) to header redirect
- [x] Fix incomplete HTML in signup.php by completing the terms error display and closing tags
- [x] Test the changes locally to ensure it works
- [x] Connect signup_old.html to the backend: Remove client-side JavaScript form handling and keep only password strength checker
- [x] Ensure signup_old.html is well connected to other codes
