# NOT FOR PRODUCTION USAGE!!!

## auth service
main problem:
- we have lot's of domains
- we need to make sso and remember it in any subdomain if exists
- we have different auth ways

![Principal Scheme](https://raw.githubusercontent.com/ekadesign/gibdd-test-task/main/user%20primitive%20way.png)

## features that I've made
- auth service with different auth ways
- basic auth guard
- JWT keys used by default
- SDK for easy access which contains middleware and user model (by contract)
- rate limitter for sms (mock + smsaero drivers)
- reset password included (mock + mailgun drivers)
- extendable
- independent
- DB agnostic
- postman collection included


![DB scheme](https://raw.githubusercontent.com/ekadesign/gibdd-test-task/main/main.png)



