
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield("title")</title>

    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/misc/Farsi-digits/font-face.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <script src="https://unpkg.com/lucide@latest"></script>
    @yield("head")
    @yield("style")
</head>


@yield("nav")


@yield("main")

@yield("footer")


<script>


    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Vazirmatn', 'sans-serif'] },
                colors: {
                    brand: {
                        dark: '#0f262a',
                        primary: '#10b981',
                        secondary: '#034f36',
                    }
                }
            }
        }
    }
</script>
 @yield("js")


<script>
    lucide.createIcons();
</script>

</body>
</html>