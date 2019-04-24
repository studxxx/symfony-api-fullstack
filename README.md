

## JWT

Generate private.pem file
```bash
openssl genrsa -out config/jwt/private.pem -aes256 4096
```
Generate public.pem file
```bash
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```