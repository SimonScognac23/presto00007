



let swiperWrapper = document.querySelector('.swiper-wrapper');
// swiperWrapper è la classe utilizzata da swiperJs



// array di recensioni 
let reviews = [
    { img: 'https://cdn.wccftech.com/wp-content/uploads/2023/05/WCCFwarhammer40kspacemarine2-728x410.jpg', description: `Ultramarines` },
    { img: 'https://s1.qwant.com/thumbr/474x338/2/7/c369748b69d4a551df1912ff1dca450d46dff20eb8622c7b4232ce4cbae81c/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.AWoUwppjIKdh-aIU0GLU6wHaFS%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Orks` },
    { img: 'https://s1.qwant.com/thumbr/474x266/a/1/c02ba0a366d41c320702eb6050158c106c735e24314d0bb8bf8e8ce789ef33/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.tSZ4M1sv7wFP3HdKwZqAxQHaEK%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Imperial Knights` },
    { img: 'https://s2.qwant.com/thumbr/474x296/d/d/20fcc3afea4705f5ee0953cc00e2d3cbbdb9154aabd5ebecb4f49af47029e3/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.G2F7fXo4n5qs3rP90nEwhwHaEo%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Necron` },
    { img: 'https://s2.qwant.com/thumbr/474x266/c/b/23036be6724a9d2b99cff28e0c23cae3c4bbca6302d85b3d17bc6ad46358e3/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.g2NlVs7HF3NZyqFp1JV0LAHaEK%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Tyranids` },
    { img: 'https://s2.qwant.com/thumbr/474x677/f/f/9dec2966f578aa29ed6de92d15bbe6e754a5ba96d03ba25d0838ada3a2b943/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.-l_UyVRc1EHiXlXl-zLaagHaKl%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Drukhari` },

    { img: 'https://s2.qwant.com/thumbr/474x266/5/b/5579923ac3ab69e5868dd2a747348e9a8cb589611b7719e7594a9b42729138/OIP.aGYqO4IBX18lT6NFO_70ZQHaEK.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%2Fid%2FOIP.aGYqO4IBX18lT6NFO_70ZQHaEK%3Fpid%3DApi&q=0&b=1&p=0&a=0', description: `Dark Angels` },

    { img: 'https://s2.qwant.com/thumbr/474x266/e/d/53d21e9181e92019db536a80c098d2de8b1ffaeed45cddea644b7520c8f276/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.5rpiySiw5CaU5vjy_KGhFAHaEK%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Astra Militarum` },

    { img: 'https://s1.qwant.com/thumbr/474x517/a/1/1c8882c118ede8ea08dc1e7f5ef6f8384f4c8a8016abde6869036d85c28c0b/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.oCT_1S6SU8xlYiGu9D-pBwHaIF%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Blood Angels` },
    { img: 'https://s1.qwant.com/thumbr/474x266/0/a/489918331dade20f9d28ea4d2c50c16f7bd8134be68c9582b9488803ca93fd/OIP.jdNDN8Hdwb03h5Yo42OohgHaEK.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%2Fid%2FOIP.jdNDN8Hdwb03h5Yo42OohgHaEK%3Fpid%3DApi&q=0&b=1&p=0&a=0', description: `Deathwatch` },
    { img: 'https://s1.qwant.com/thumbr/474x317/b/a/2ae1c19e74cd425bddc96b4cfdd7c0c21cf0b5c4f6804dc0f0ff2cc38591f4/th.jpg?u=https%3A%2F%2Ftse2.explicit.bing.net%2Fth%3Fid%3DOIP.nCYzE6Zw97FK699DQ9d-oAHaE9%26pid%3DApi&q=0&b=1&p=0&a=0', description: `Khorne Daemons` },
];




reviews.forEach((recensione) => {
    let div = document.createElement('div');
    div.classList.add('swiper-slide');

    div.innerHTML = `
        <div class="card text-white bg-dark border-warning mb-3 shadow" style="height: 300px; overflow: hidden; border-width: 2px;">
            <img src="${recensione.img}" alt="user image" class="card-img-top" style="height: 200px; object-fit: cover; border-bottom: 2px solid gold;">
            <div class="card-body d-flex align-items-center justify-content-center p-2" style="background-color: #111;">
                <p class="card-text text-warning text-center m-0" style="font-weight: bold;">${recensione.description}</p>
            </div>
        </div>
    `;

    swiperWrapper.appendChild(div);
});








let stars = document.querySelectorAll('.star') // stars è il div vuoto che deve contenere le stelle
// ciclo per ottenere le stelle date in recensione

stars.forEach((star, index) => {  // per ogni stella devi rank volte creare una stella ed appenderlo usando l'indice, in pratica sto ciclando dentro l'array
    for (let i = 1; i <= reviews[index].rank; i++) {  // vai su reviews in posizione index.rank, in pratica sto dicendo: per Simone devi ciclare da 1 a 5.. e cosi via con gli altri

        // Ora creiamo la stella per la recensione
        let icon = document.createElement('i');
        icon.classList.add('fa-solid', 'fa-star') // al tag <i> ci aggiungi e classi fa-star e fa-solid
        star.appendChild(icon);
    }

    // ora per fare apparire anche le stelle vuote
    let difference = 5 - reviews[index].rank;  // a  5 gli togliamo il voto
    for (let i = 1; i <= difference; i++) {

        // Ora creiamo la stella per la recensione
        let icon = document.createElement('i');
        icon.classList.add('fa-regular', 'fa-star')
        star.appendChild(icon);
    }

}); //partendo dalla nodeList delle stelle, per ogni signola star devi rank volte creare una stella e appenderla




//Swiper sempre per ultimo

const swiper = new Swiper('.swiper1', {  // crea un nuovo oggetto di classe swiper e poi vuole una classe, ossia un selettore CSS .swiper che gli fa capire che l'elemento in questione è il div con classe swiper, ovviamente dentro .swiper ci sta un query selector
    // .swiper sto catturando l'elemento di classe swiper

    // Optional parameters

    slidesPerView: 3, // numero di slide visibili nella versione desktop
    spaceBetween: 30,
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },

    loop: true, // per farlo ciclare in modo infinito senza fermarsi

    autoplay: {
        delay: 0, // nessun ritardo tra una transizione e l'altra, per scorrimento continuo
        disableOnInteraction: false, // non bloccare lo scorrimento automatico quando l'utente interagisce (touch/swipe)
        pauseOnMouseEnter: false, // non mettere in pausa se il mouse è sopra la slider
    },

    speed: 4000, // durata della transizione in millisecondi (più alto = scorrimento più lento e fluido)

    // aggiungiamo i breakpoints per rendere lo swiper responsivo
    breakpoints: {
        0: {
            slidesPerView: 1, // su schermi piccolissimi mostra una sola slide
        },
        576: {
            slidesPerView: 2, // su smartphone (larghezza ≥ 576px) mostra 2 slide
        },
        768: {
            slidesPerView: 3, // su tablet e desktop (≥ 768px) torna a 3 slide
        }
    }

});

