<?php
function display_about_us(){
    echo <<<HTML
    <main class="about-page displace-hide animate-in">
    <section class="about-section">
      <h1 class="section-title">Rreth Kompanisë</h1>
      <p class="about-text">
        Kompania jonë është e përkushtuar në ofrimin e shërbimeve dhe produkteve cilësore për klientët tanë.
        Ne besojmë në inovacion, përkushtim dhe kujdes maksimal ndaj konsumatorit. Çdo produkt që ne ofrojmë është
        përzgjedhur me kujdes për të garantuar një eksperiencë të jashtëzakonshme blerjeje.
      </p>
    </section>

    <section class="contact-section">
      <h2 class="section-title">Na Kontaktoni</h2>
      <div class="contact-details">
        <p><strong>Email:</strong> info@kompania.com</p>
        <p><strong>Tel:</strong> +355 67 123 4567</p>
        <p><strong>Adresa:</strong> Fakulteti i Shkencave te Natyres</p>
      </div>
    </section>

    <section class="map-section">
      <h2 class="section-title">Vendndodhja Jonë</h2>
      <div class="map-container">
        <iframe
          src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2995.8188970980555!2d19.81385217457322!3d41.33455129927313!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1350310db0518c2f%3A0x66adc753e7cc308!2sFaculty%20of%20Natural%20Sciences!5e0!3m2!1sen!2s!4v1748207922861!5m2!1sen!2s"
          width="100%"
          height="300"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade">
        </iframe>
      </div>
    </section>
  </main>
  HTML;
}