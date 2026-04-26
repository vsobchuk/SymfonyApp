# Ogolne notatki
- Wsiędzie gdzie używane są repozytoria, warto używać interfejsów jako type-hinta, nie robiłem togo dla ekonomiji czasu
- Ogólne zasady testów jednostkowych:
- - testowany musi być każdy publiczny metod serwisu oraz serwis nie musi mieć dostępu do zewnętrznych zależności (Baza danych / API / inne serwisy).
- - reposytoriumy mogą być testowanę, jeżeli posiadają logikę

# Zadanie 1
## Update Symfony version
- Update Symfony do wersji 7.4, nie robiłem update na 8.4 żeby nie spędzić na daremno czasu
- Update PHP do 8.4
- naprawa Route Anotation w HomeController
- AuthController - używanie reposytorium do logowania
- jeden tekst dla wszystkich błędów, żeby uniemożliwić brutteforce loginu
- nie ufać user-inputu i nie klaść go do schowka sesji, używać znaczeń z BD natomiast
- nie używać ManagerRegistry w HomeController
- wszystko związane z Like jest odpowiednie do reszty projektu + Unit Test dla serwisu
- imiona funkcji dislikePhoto i likePhoto, logika

# Zadanie 2
## Token powinien zostać zapisany w bazie danych
- zakładamy, że jeden user może mieć tylko jeden token. Robimy nową kolumnę w tabeli usera. 
- Jeżeli założyć, że jedyn user może mieć dwa i więcej tokenów, warto byłoby zrobić osobną tablicę
## Import zdjęć
- Będą importowane tylko nowe zdjęcią
- Będzie pokazywany komunikat o ilości importowanych zdjęć

# Zadanie 3
- Filtr po polu username - chyba chodzi o opach name oraz last_name z tablicy users. Te dane są widoczne dla użytkownika na stronie domowej i właśnie ich on będzie szukac
- W prawdziwym projekcie trzeba by zrobić indeksację kolumn tablic, po jakich odbywa się filtracja, albo użyć ElasticSearch dla szukania tekstu
- Filtrowanie po taken_at wygląda jak standardowy input typu date browsera, oczywiście trzeba bylo by dla pięknoty zasosować jakoogoś pluginu albo więcej czasu spędzić na robienie CSS+ JS

# Zadanie 4
- ExRated phoenix-api/deps/ex_rated/lib/ex_rated.ex:217 używa > dla porówninania, dla tego żądane liczby muszą być na 1 mniejsze
- niestety nie miałem doświadczenia z Elixirem wcześniej, nie znam co to jest OTP, mam nadzieję będe miał możliwość pożnać go bardziej szczególnie

### lista To Do niestety nie jest pusta, 3 zadania zrobione, przechodzę do 4, na niego chyba strace dużo czasu :/

### To Do
- Login za pomocą login + password, przes formę + CSRF
- Linki Like Photo - musi być POST request
- stworzyć Application Testy
- zabrać część logiki z dislikePhoto do LikeService, zrobić metod toggleLike
- rate limiter: https://dev.to/mnishiguchi/rate-limiter-for-phoenix-app-3j2n#:~:text=2.0%22%7D%20%5D%20end-,Implement%20a%20plug,to_list()%20%7C%3E%20Enum.