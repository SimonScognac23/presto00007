<x-layout>
  <!-- SECTION: Container generale con sfondo e centratura verticale -->
  <div class="container-fluid register-bg d-flex align-items-center justify-content-center min-vh-100">

    <!-- SECTION: Riga centrale contenente il form -->
    <div class="row justify-content-center text-center w-100">

      <!-- SECTION: Colonna responsive che contiene titolo, form e logo -->
      <div class="col-12 col-md-8 col-lg-6 position-relative">

        <!-- Aggiungi il logo piccolo a destra -->
        <img src="https://s1.qwant.com/thumbr/185x185/8/f/1fff6046b1e524daf03b449582d2f914ad654dba5129a107975b2bb16439b0/th.jpg?u=https%3A%2F%2Ftse.mm.bing.net%2Fth%3Fid%3DOIP.souk9VkX3bYXHRKFGQzkyAAAAA%26pid%3DApi&q=0&b=1&p=0&a=0" alt="Logo" class="logo">

        <!-- SECTION: Titolo della pagina -->
        <h1 class="mb-4 text-uppercase fw-bold custom-subtitle text-black">
          Registrati
        </h1>

        <!-- SECTION: Form di registrazione -->
        <form
          action="{{ route('register') }}"    
          method="POST"                       
          class="bg-white text-dark p-4 p-md-5 rounded-4 shadow-lg mx-auto"  
          novalidate
          onsubmit="return validateCompleteForm()"                        
        >
          @csrf  <!-- Protezione CSRF di Laravel -->

          <!-- INPUT: Email -->
          <div class="mb-4 text-start">
            <label for="email" class="form-label fw-semibold text-black">Email</label>
            <input
              type="email"
              name="email"
              id="email"
              class="form-control @error('email') is-invalid @enderror"
              placeholder="{{ __('ui.email_placeholder') }}"
              value="{{ old('email') }}"
              required
            >
            @error('email')
              <div class="text-danger mt-1">
                <small>{{ $message }}</small>
              </div>
            @enderror
          </div>

          <!-- INPUT: Nome -->
          <div class="mb-4 text-start">
            <label for="name" class="form-label fw-semibold text-black">{{ __('ui.name') }}</label>
            <input
              type="text"
              name="name"
              id="name"
              class="form-control @error('name') is-invalid @enderror"
              placeholder="{{ __('ui.name_placeholder') }}"
              value="{{ old('name') }}"
              required
            >
            @error('name')
              <div class="text-danger mt-1">
                <small>{{ $message }}</small>
              </div>
            @enderror
          </div>

          <!-- INPUT: Password con validazione personalizzata -->
          <div class="mb-4 text-start">
            <label for="password" class="form-label fw-semibold text-black">Password</label>
            <input
              type="password"
              name="password"
              id="password"
              class="form-control @error('password') is-invalid @enderror"
              placeholder="{{ __('ui.password_placeholder') }}"
              required
              minlength="8"
              oninput="validatePasswordRealTime()"
            >
            
            <!-- Validazione in tempo reale con gli stessi criteri del tuo codice PHP -->
            <div class="mt-2" id="password-requirements">
              <small class="d-block">
                <span id="length-check" class="text-muted">
                  <i class="bi bi-circle me-1"></i>Almeno 8 caratteri
                </span>
              </small>
              <small class="d-block">
                <span id="number-check" class="text-muted">
                  <i class="bi bi-circle me-1"></i>Almeno 1 numero
                </span>
              </small>
              <small class="d-block">
                <span id="uppercase-check" class="text-muted">
                  <i class="bi bi-circle me-1"></i>Almeno 1 maiuscola
                </span>
              </small>
              <small class="d-block">
                <span id="special-check" class="text-muted">
                  <i class="bi bi-circle me-1"></i>Almeno 1 carattere speciale (!@#$)
                </span>
              </small>
            </div>

            @error('password')
              <div class="text-danger mt-1">
                <small>{{ $message }}</small>
              </div>
            @enderror

            <!-- Messaggio di errore per validazione JavaScript -->
            <div id="password-error" class="text-danger mt-1" style="display: none;">
              <small>La password non rispetta tutti i requisiti richiesti</small>
            </div>
          </div>

          <!-- INPUT: Conferma Password -->
          <div class="mb-5 text-start">
            <label for="password_confirmation" class="form-label fw-semibold text-black">{{ __('ui.confirm_password') }}</label>
            <input
              type="password"
              name="password_confirmation"
              id="password_confirmation"
              class="form-control @error('password_confirmation') is-invalid @enderror"
              placeholder="{{ __('ui.confirm_password_placeholder') }}"
              required
              minlength="8"
              oninput="validatePasswordMatch()"
            >
            
            <!-- Messaggio per conferma password -->
            <div id="password-match-error" class="text-danger mt-1" style="display: none;">
              <small>Le password non coincidono</small>
            </div>
            <div id="password-match-success" class="text-success mt-1" style="display: none;">
              <small><i class="bi bi-check-circle-fill me-1"></i>Le password coincidono</small>
            </div>

            @error('password_confirmation')
              <div class="text-danger mt-1">
                <small>{{ $message }}</small>
              </div>
            @enderror
          </div>

          <!-- SECTION: Bottone di invio -->
          <div class="d-grid">
            <button type="submit" id="submit-btn" class="btn btn-lg fw-semibold shadow btn-black-text">
              {{ __('ui.register') }}
            </button>
          </div>
            
        </form>
        <!-- END FORM -->

      </div> 
    </div> 
  </div> 




<!--  JS PER CONFERMA PASSWORD ------------------------------------------------------- -->


<script>
// Variabili globali per tracciare la validazione (stesso approccio del tuo codice PHP)
let passwordValid = false;     // Equivale a $isValid nel tuo PHP - traccia se la password rispetta tutte le regole
let passwordsMatch = false;    // Traccia se password e conferma coincidono

// FUNZIONI DI VALIDAZIONE (tradotte dal tuo codice PHP originale)

// 1. Controllo lunghezza (checkLength dal tuo PHP)
function checkLength(password) {
    return password.length >= 8;   // Equivale a strlen($password) >= 8 nel tuo PHP
}

// 2. Controllo numero (checkNumber dal tuo PHP)
function checkNumber(password) {
    for (let i = 0; i < password.length; i++) {     // Equivale al for ($i=0; $i < strlen($password); $i++) del tuo PHP
        if (!isNaN(password[i]) && password[i] !== ' ') {    // !isNaN() è l'equivalente di is_numeric() in PHP
            return true;    // Se trova un numero, ritorna true e esce (equivale al return true nel tuo PHP)
        }
    }
    return false;       // Se non trova numeri, ritorna false
}

// 3. Controllo maiuscola (checkUppercase dal tuo PHP)
function checkUppercase(password) {
    for (let i = 0; i < password.length; i++) {     // Stesso ciclo for del tuo PHP
        if (password[i] >= 'A' && password[i] <= 'Z') {    // Equivale a ctype_upper($password[$i]) nel tuo PHP
            return true;    // Equivale al return true quando trova una maiuscola
        }
    }
    return false;       // Se non trova maiuscole, ritorna false (implicito nel tuo PHP)
}

// 4. Controllo carattere speciale (checkSpecialChar dal tuo PHP)
function checkSpecialChar(password) {
    const SPECIAL_CHARS = ["!", "@", "#", "$"];    // Equivale alla const SPECIAL_CHARS del tuo PHP

    for (let i = 0; i < password.length; i++) {     // Stesso ciclo for del tuo PHP
        if (SPECIAL_CHARS.includes(password[i])) {    // includes() è l'equivalente di in_array() in PHP
            return true;    // Equivale al return true del tuo PHP
        }
    }
    return false;       // Se non trova caratteri speciali, ritorna false
}

// Funzione principale di validazione (tradotta dal tuo checkPassword)
function validatePasswordRealTime() {
    const password = document.getElementById('password').value;    // Prende il valore dall'input (equivale a $password nel tuo PHP)
    let allValid = true;    // Equivale a $isValid = true nel tuo PHP

    // Controllo lunghezza
    const lengthCheck = document.getElementById('length-check');    // Prende l'elemento HTML per mostrare il risultato
    if (checkLength(password)) {    // Equivale a if (!checkLenght($password)) ma con logica invertita
        lengthCheck.className = 'text-success';    // Cambia il colore in verde (successo)
        lengthCheck.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Almeno 8 caratteri';    // Mostra icona verde + testo
    } else {
        lengthCheck.className = 'text-muted';    // Mantiene il colore grigio (non soddisfatto)
        lengthCheck.innerHTML = '<i class="bi bi-circle me-1"></i>Almeno 8 caratteri';    // Mostra icona vuota + testo
        allValid = false;    // Equivale a $isValid = false nel tuo PHP
    }

    // Controllo numero
    const numberCheck = document.getElementById('number-check');    // Elemento HTML per il feedback visivo
    if (checkNumber(password)) {    // Equivale a if (!checkNumber($password)) ma invertito
        numberCheck.className = 'text-success';    // Verde = requisito soddisfatto
        numberCheck.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Almeno 1 numero';    // Icona check verde
    } else {
        numberCheck.className = 'text-muted';    // Grigio = requisito non soddisfatto
        numberCheck.innerHTML = '<i class="bi bi-circle me-1"></i>Almeno 1 numero';    // Icona vuota grigia
        allValid = false;    // Segna la password come non valida
    }

    // Controllo maiuscola
    const uppercaseCheck = document.getElementById('uppercase-check');    // Elemento HTML per maiuscole
    if (checkUppercase(password)) {    // Equivale a if (!checkUppercase($password)) ma invertito
        uppercaseCheck.className = 'text-success';    // Verde = ha maiuscole
        uppercaseCheck.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Almeno 1 maiuscola';    // Check verde
    } else {
        uppercaseCheck.className = 'text-muted';    // Grigio = nessuna maiuscola
        uppercaseCheck.innerHTML = '<i class="bi bi-circle me-1"></i>Almeno 1 maiuscola';    // Cerchio vuoto
        allValid = false;    // Password non valida
    }

    // Controllo carattere speciale
    const specialCheck = document.getElementById('special-check');    // Elemento HTML per caratteri speciali
    if (checkSpecialChar(password)) {    // Equivale a if (!checkSpecialChar($password)) ma invertito
        specialCheck.className = 'text-success';    // Verde = ha caratteri speciali
        specialCheck.innerHTML = '<i class="bi bi-check-circle-fill me-1"></i>Almeno 1 carattere speciale';    // Check verde
    } else {
        specialCheck.className = 'text-muted';    // Grigio = nessun carattere speciale
        specialCheck.innerHTML = '<i class="bi bi-circle me-1"></i>Almeno 1 carattere speciale';    // Cerchio vuoto
        allValid = false;    // Password non valida
    }

    passwordValid = allValid;    // Salva lo stato globale della validazione (equivale a $isValid nel tuo PHP)
    updateSubmitButton();    // Aggiorna lo stato del bottone di submit

    // Ricontrolla la corrispondenza se la conferma è già stata inserita
    if (document.getElementById('password_confirmation').value) {    // Se l'utente ha già scritto nella conferma
        validatePasswordMatch();    // Controlla se le password coincidono
    }
}

function validatePasswordMatch() {
    const password = document.getElementById('password').value;    // Password principale
    const confirmation = document.getElementById('password_confirmation').value;    // Conferma password
    const errorDiv = document.getElementById('password-match-error');    // Div per mostrare errore
    const successDiv = document.getElementById('password-match-success');    // Div per mostrare successo

    if (confirmation === '') {    // Se la conferma è vuota
        errorDiv.style.display = 'none';    // Nascondi messaggio di errore
        successDiv.style.display = 'none';    // Nascondi messaggio di successo
        passwordsMatch = false;    // Le password non coincidono (perché una è vuota)
    } else if (password === confirmation) {    // Se le password sono identiche
        errorDiv.style.display = 'none';    // Nascondi errore
        successDiv.style.display = 'block';    // Mostra messaggio di successo
        passwordsMatch = true;    // Segna che le password coincidono
    } else {    // Se le password sono diverse
        errorDiv.style.display = 'block';    // Mostra messaggio di errore
        successDiv.style.display = 'none';    // Nascondi successo
        passwordsMatch = false;    // Le password non coincidono
    }

    updateSubmitButton();    // Aggiorna lo stato del bottone dopo ogni controllo
}

function updateSubmitButton() {
    const submitBtn = document.getElementById('submit-btn');    // Prende il bottone di submit
    const email = document.getElementById('email').value;    // Valore dell'email
    const name = document.getElementById('name').value;    // Valore del nome

    // Abilita il bottone solo se tutti i campi sono validi
    if (passwordValid && passwordsMatch && email && name) {    // Tutti i controlli devono essere true
        submitBtn.disabled = false;    // Abilita il bottone
        submitBtn.style.opacity = '1';    // Rende il bottone completamente visibile
    } else {    // Se anche solo un controllo fallisce
        submitBtn.disabled = true;    // Disabilita il bottone
        submitBtn.style.opacity = '0.6';    // Rende il bottone semi-trasparente (visual feedback)
    }
}

// Funzione chiamata al submit del form (simile al tuo ciclo do-while)
function validateCompleteForm() {
    const password = document.getElementById('password').value;    // Prende la password finale
    const passwordError = document.getElementById('password-error');    // Div per errori generali

    // Esegui tutte le validazioni prima dell'invio
    validatePasswordRealTime();    // Ricontrolla tutti i requisiti della password
    validatePasswordMatch();    // Ricontrolla che le password coincidano

    // Se la password non è valida, mostra errore e blocca il submit
    if (!passwordValid) {    // Equivale alla logica del tuo if (!$isValid) nel PHP
        passwordError.style.display = 'block';    // Mostra il messaggio di errore generale
        return false; // Previeni l'invio del form (equivale al ciclo do-while del tuo PHP che richiede una nuova password)
    } else {
        passwordError.style.display = 'none';    // Nascondi l'errore se la password è valida
    }

    if (!passwordsMatch) {    // Se le password non coincidono
        return false; // Previeni l'invio del form
    }

    return true; // Permetti l'invio del form (equivale al break; nel tuo PHP quando tutto è valido)
}

// Inizializza al caricamento della pagina
document.addEventListener('DOMContentLoaded', function () {    // Quando la pagina è completamente caricata
    updateSubmitButton();    // Controlla subito lo stato del bottone (probabilmente sarà disabilitato)

    // Event listeners per tutti i campi - si attivano quando l'utente scrive
    document.getElementById('email').addEventListener('input', updateSubmitButton);    // Ogni volta che cambia l'email, ricontrolla il bottone
    document.getElementById('name').addEventListener('input', updateSubmitButton);    // Ogni volta che cambia il nome, ricontrolla il bottone
});
</script>



</x-layout>