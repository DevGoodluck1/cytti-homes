# TODO: Fix Login/Signup Database Connection Issue

## Problem Analysis
- Signup works: User data is saved to database
- Sign-in failing after logging in: Session not persisting

## Root Causes Identified
1. **login.html** has incorrect link: `<a href="signup.html">` should be `<a href="signup.php">`
2. **login.html** has a form that may have JavaScript issues
3. Possible session handling issue between signup and login

## Fix Plan
- [ ] Fix login.html - change signup link from signup.html to signup.php
- [ ] Ensure login_process.php is working correctly
- [ ] Verify session handling in dashboard.php
- [ ] Test the flow

## Files to Edit
- login.html (link fix)
