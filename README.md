# Test app for Cyber-Telecom

## Installation
```bash
cp docker-compose.override.dist.yml docker-compose.override.yml

make up
```

### Install pre-commit hook
```bash
cp ./pre-commit .git/hooks/
chmod +x .git/hooks/pre-commit
```

### Import models and generation
```bash
make cli

php artisan migrate

php artisan app:parse-models <remoteModelsPage> -v

php artisan app:parse-model-generations -v

```