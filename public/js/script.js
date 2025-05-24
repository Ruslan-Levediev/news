document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.read-more').forEach(button => {
        button.addEventListener('click', function() {
            const article = this.closest('article');
            const short = article.querySelector('.news-short');
            const full = article.querySelector('.news-full');

            if (short.style.display === 'none') {
                short.style.display = 'block';
                full.style.display = 'none';
                this.textContent = 'Читати далі';
            } else {
                short.style.display = 'none';
                full.style.display = 'block';
                this.textContent = 'Згорнути';
            }
        });
    });
});
