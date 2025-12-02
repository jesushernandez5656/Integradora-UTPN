self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open("app-cache").then((cache) => {
            return cache.addAll([
                "index.php",
                "manifest.json",
                "public/css/style.css",
                "public/js/main.js",
                "public/img/icon-192.png",
                "public/img/icon-512.png"
            ]);
        })
    );
});

self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches.match(event.request).then((resp) => {
            return resp || fetch(event.request);
        })
    );
});
