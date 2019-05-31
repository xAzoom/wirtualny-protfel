# Projekt - Programowanie Obiektowe 
## Temat: Implementacja Wirtualnego Portfela

### 1. Wstęp

Tematem projektu jest implementacja wirtualnego portfela jako REST API z wykorzystaniem strategii Event Sourcingu.

Terminologia:

API - interfejs programistyczny aplikacji. Umożliwia komunikację między programami.  
REST - styl architektury oprogramowania, standard okreslający zasady projektowania API.  
Event Sourcing - wzorzec, strategia tworzenia istotnych części aplikacji (z podejścia biznesowego), które wymagają rejestrowania wykonywanych akcji.

Biblioteki pomocnicze wykorzystane w projekcie:
- Silex (rama, fundament zawierający istotne funkcje wspomagające budowę projektu oraz komunikację po protokole HTTP)
- Composer - system zarządzania pakietami w języku PHP
- Prooph - biblioteka oferująca komponenty obsługujące Event Sourcing

### 2. Przeznaczenie aplikacji
Istotą programu jest wydzielenie obsługi wirtualnego portfela, który jest częścią większego systemu. Zabieg ten zwiększa skalowalność produktu, która może być oczekiwana w przypadku tworzenia wydajnych aplikacji przetwarzających dużą ilość informacji.  

Oprogramowanie umożliwia:
- tworzenie portfela,
- pobranie informacji o środkach w portfelu,
- aktywację portfela,
- dezaktywacja portfela,
- wpłatę środków do portfela,
- wypłatę środków z portfela.

### 3. Architektura aplikacji
index.php  
Główny plik programu inicjujący ramę, połączenie z repozytorium przechowujące zdarzenia oraz trasę programu.

vendor  
Katalog zawierający wszystkie biblioteki programu.

app/eventStoreInit.php  
Funkcja tworzące obiekt repozytorium przechowujący zdarzenia.

app/routesInit.php  
Funkcja tworząca trasy do odpowiednich adresów stron.

Controller/WalletController.php  
Klasa obsługująca akcje wykonywane przez użytkownika.  
* Pola:  
    protected eventStore – pole przechowujący obiekt zwracający przez funkcje eventStoreInit

* Metody:  
    CreateWollet() – metoda tworząca portfel i zapisująca go w repozytorium
    
    GetBalance(string $id) – metoda zwracająca stan konta portfela o podanym id

     Deposit(string $id, int $amount) – metoda dodająca określoną kwotę do portfela

	Withdraw(string $id, int $amount) - 	metoda wypłacająca określoną kwotę z portfela

	Activate(string $id) – metoda aktywująca portfel

	Deactivate(string $id) – metoda dezaktywująca konto


Infrastructure/WalletRepository.php  
Klasa obsługująca odczyt i zapis zdarzeń w zewnętrznym magazynie.
* Metody:  
	save(Wallet $wallet) – metoda zapisująca portfel w repozytorium
	
    get(Uuid $uuid) – metoda zwracająca obiekt portfela z repozytorium

Infrastructure/IWalletRepository.php  
Interfejs gwarantyjący obecność metod zapisu i odczytu zdarzeń z zewnętrznego magazynu.
* Metody:  
	save(Wallet $wallet) – interfejs metody zapisująca portfel w repozytorium
	
    get(Uuid $uuid) – interfejs metody zwracający obiekt portfela z repozytorium

Middleware/Authenticate.php  
Klasa uwierzytelniająca użytkownika.
* Metody:  
    authenticate(Request $request) – metoda statyczna sprawdzająca poprawność nagłówka w rządaniu (Request), uwierzytelniając w ten sposób użytkownika

Model/Wallet.php  
Klasa reprezentująca wirtualny portfel.
* Pola:  
	private $id – id portfela  
	private $balance – obiekt reprezentujący pieniądze
	private $isActivate – pole określające czy portfel jest aktywny

* Metody:  
	getId() – metoda zwracająca ID portfela

	getBallance() – metoda zwracająca stan konta

	getIsActivate() – metoda zwracająca informację o tym czy portfel jest aktywny

	Create(string $currency) – metoda tworząca zdarzenie reprezentujące stworzenie
	portfela
	
    Deposit(int $amount) – metoda tworząca zdarzenie reprezentujące wpłatę środków
do portfela

    Withdraw(int $amount) – metoda tworząca zdarzenie reprezentujące wypłatę środków
z portfela.

    Activate() – metoda tworząca zdarzenie reprezentujące aktywację portfela

    Deactivate() – metoda reprezentująca zdarzenie dezaktywacji konta

    aggregateId() – metoda zwracająca id porfela jako string

    apply(AggregateChanged $event) – metoda obsługująca wykonywanie konkretnych czynności w zależności od otrzymanego zdarzenia

    createWallet(WalletCreated $event) – metoda tworząca portfel na podstawie zdarzenia

    depositToWallet(WalletDeposited $event) – metoda dodająca określoną ilość środków do portfela na podstawie otrzymanego zdarzenia

    withdrawFromWallet(WalletWithdrew $event) – metoda odejmująca określoną ilość środków z portfela na podstawie otrzymanego zdarzenia

    activateWallet(WalletActivated $event) – metoda ustawiająca status konta na podstawie otrzymanego zdarznia

    deactivateWallet(WalletActivated $event) – metoda ustawiająca status konta na podstawie otrzymanego zdarznia

Model/WalletEvents/WalletCreated.php  
Klasa reprezentująca wykonanie zdarzenia – stworzenie portfela.

Model/WalletEvents/WalletActivated.php  
Klasa reprezentująca wykonanie zdarzenia – aktywacji portfela.

Model/WalletEvents/WalletDeactivated.php  
Klasa reprezentująca wykonanie zdarzenia – dezaktywacji portfela.

Model/WalletEvents/WalletDeposited.php  
Klasa reprezentująca wykonanie zdarzenia – wpłaty środków na konto.

Model/WalletEvents/WalletWithdrew.php  
Klasa reprezentująca wykonanie zdarzenia – wypłaty środków z konta.

Model/WalletEvents/WalletWithdrew.php  
Klasa reprezentująca wykonanie zdarzenia – wypłaty środków z konta.

Exceptions/WalletException.php  
Klasa reprezentująca wyjątki w obsłudze portfela.

### 4. Diagram zależności klas
![Diagram klas](https://i.imgur.com/QadLGE2.png "Diagram klas")

### 5. Uruchomienie aplikacji

Program jako aplikacja webowa napisana w PHP potrzebuje serwera interpretującego język PHP w wersji >= 7.1 oraz MySQL >= 5.7.9

Przykładowy serwer PHP oferuje aplikacja XAMPP (apachefriends.org).

Na potrzeby demonstracyjne aplikacja jest już podłączona do zewnętrznego serwera MySQL.

### 6. Sposób komunikacji
Komunikacja z aplikacją odbywa się po protokole HTTP, korzystając z odpowiednich metod tego protokołu.

_POST : /_  
Stworzenie nowego portfela.  
Kod odpowiedzi: 201  
Treść: Obiekt typu JSON przechowujący id nowego portfela.
 
_GET : /id_  
Informacja o stanie środków portfela.  
Kod odpowiedzi: 200  
Treść: Obiekt typu JSON przechowujący id nowego portfela.

_PUT : /activate/id_  
Aktywacja porfela.  
Kod odpowiedzi: 204

W przypadku podania ID portfela, które nie istnieje: 404  

_PUT : /deactivate/id_  
Dezaktywacja porfela.  
Kod odpowiedzi: 200  

W przypadku podania ID portfela, które nie istnieje: 404

_PUT : /withdraw/id/ilosc_  
Wypłata środków z portfela  
Kod odpowiedzi: 204

W przypadku podania id które nie istnieje, złej ilość środków do wypłaty lub próby wypłaty na nieaktywnym portfelu: 404
