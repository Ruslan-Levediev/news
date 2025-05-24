<h1>Додати новину</h1>

<form method="post" action="/project/public/news/add_post" enctype="multipart/form-data">
    <label>Заголовок:<br>
        <input type="text" name="title" required placeholder="Заголовок">
    </label><br><br>

    <label>Текст новини:<br>
        <textarea name="content" rows="10" cols="50" required placeholder="Текст новини"></textarea>
    </label><br><br>

    <label>Завантажити зображення:<br>
        <input type="file" name="image" accept="image/*">
    </label><br><br>

    <button type="submit">Додати</button>
</form>

<p><a href="/project/public/news">Повернутися до новин</a></p>
