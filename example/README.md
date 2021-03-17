# Example

This is an example to Mock an OAuth 2.0 server with OpenID Connect implemented.

I recommand to [read this](https://oauth2.thephpleague.com/authorization-server/auth-code-grant/) first.

## Setup
```sh
# start the service application
php -S localhost:8000 -t example

# get the tokens using the client
php example/get_tokens
```
