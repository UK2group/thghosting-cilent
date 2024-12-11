# THG Hosting Open API Client Examples

### Develop/Testing API Client using the local repository
* Using `composer.dev.json` for testing the source codes in the src directory.
* In this case when you run the command `composer install`, the local `thg/thgclient` package will be installed instead of the package from remote repository

* To install dependencies for the examples
```console
  docker-compose exec -w /app/examples thghosting-client composer install
```
* For testing the THG Hosting API client
```console
docker-compose exec -w /app/examples thghosting-client php thgHostingApi.php 
```
