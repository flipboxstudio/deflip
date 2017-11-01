## DeFlip

DeFlip is a simple starter project to make a perfect landing website, support API calls within application. It's super fast and has minimal dependency. Below is the current features:

- Robust routing
- Clean template engine
- Error handling
- Laravel Mix
- API calls

---

## Quickstart

Running:

```
composer install

php -S localhost:8000 -t public/
```

Development:

```
composer install
npm install
npm run hot

# in another terminal
php -S localhost:8000 -t public/
```

---

## Guides

To add new module you need to `{ TBD }`

### Routing

See [here](http://route.thephpleague.com/)

### Templating

See [here](http://platesphp.com/)

---

## Known Issues

When you run `npm run hot` and terminate it process, you may notice that your assets still want to load from `http://localhost:8080` (hot reload server), it caused by the `hot` file inside `public` directory is not deleted. Ideally it should be automatically deleted after `hot` process terminated. This is Laravel Mix issue, so for now, the only workaround is just manually delete this `hot` file.
