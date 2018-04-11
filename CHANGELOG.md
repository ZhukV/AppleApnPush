## v3.0.6

### Features

* Add `JwtSignatureGenerator` for available use any libraries for generating the signature from JWT token.
* Support next libraries:
    * [spomky-labs/jose](https://github.com/Spomky-Labs/jose)
    * [web-token/jwt-*](https://www.gitbook.com/book/web-token/jwt-framework)

### Impact

Make changes without impact, all code has a BC. If previously you use `SpomkyLabs`, the factory has been successfully 
creating require the generator.
