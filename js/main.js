function scrollNav() {
    const nav = document.getElementById('nav');

    if (this.scrollY >= 20) nav.classList.add('navsc'); else nav.classList.remove('navsc');
};

window.addEventListener('scroll', scrollNav);

function enableDarkMode() {
    document.documentElement.style.setProperty('--c-a', 'var(--dark-brown)');
    document.documentElement.style.setProperty('--c-b', 'var(--black)');
    document.documentElement.style.setProperty('--c-c', 'var(--lightest-brown)');
    document.documentElement.style.setProperty('--c-d', 'var(--lighter-brown)');
    document.documentElement.style.setProperty('--c-e', 'var(--dark-brown-transparent)');
    document.documentElement.style.setProperty('--c-f', 'var(--light-brown)');


    localStorage.setItem('darkModeEnabled', 'true');
}

function disableDarkMode() {
    document.documentElement.style.setProperty('--c-a', 'var(--light-brown)');
    document.documentElement.style.setProperty('--c-b', 'var(--white)');
    document.documentElement.style.setProperty('--c-c', 'var(--darkest-brown)');
    document.documentElement.style.setProperty('--c-d', 'var(--darker-brown)');
    document.documentElement.style.setProperty('--c-e', 'var(--light-brown-transparent)');
    document.documentElement.style.setProperty('--c-f', 'var(--brown');


    localStorage.removeItem('darkModeEnabled');
}

window.addEventListener('load', function() {
    var darkModePreference = localStorage.getItem('darkModeEnabled');

    const mode = document.getElementById('mode');

    
    if (darkModePreference === 'true') {
        mode.classList.add('bx-sun'); 
        mode.classList.remove('bx-moon');
        enableDarkMode();
        darkModeEnabled = true;
    } else {
        mode.classList.remove('bx-sun');
        mode.classList.add('bx-moon');
        disableDarkMode();
        darkModeEnabled = false;
    }
});

var darkModeEnabled = false;

function toggleDarkMode() {
    const mode = document.getElementById('mode');

    darkModeEnabled = !darkModeEnabled;

    if (darkModeEnabled) {
        mode.classList.add('bx-sun'); 
        mode.classList.remove('bx-moon');
        enableDarkMode();
    } else {
        mode.classList.remove('bx-sun');
        mode.classList.add('bx-moon');
        disableDarkMode();
    }
}

var mySwiper = new Swiper ('.swiper-container', {
    speed: 400,
    spaceBetween: 100,
    initialSlide: 0,
    //truewrapper adoptsheight of active slide
    autoHeight: false,
    // Optional parameters
    direction: 'horizontal',
    loop: true,
    // delay between transitions in ms
    autoplay: 5000,
    autoplayStopOnLast: false, // loop false also
    // If we need pagination
    pagination: '.swiper-pagination',
    paginationType: "bullets",
    
    // Navigation arrows
    nextButton: '.swiper-button-next',
    prevButton: '.swiper-button-prev',
    
    // And if we need scrollbar
    //scrollbar: '.swiper-scrollbar',
    // "slide", "fade", "cube", "coverflow" or "flip"
    effect: 'slide',
    // Distance between slides in px.
    spaceBetween: 60,
    //
    slidesPerView: 2,
    //
    centeredSlides: true,
    //
    slidesOffsetBefore: 0,
    //
    grabCursor: true,
})        
