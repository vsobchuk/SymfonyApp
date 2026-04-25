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
- zakładamy że jeden user może mieć tylko jeden token. Robimy nową kolumnę w tabeli usera. 
- Jeżeli założyć że jedyn user może mieć dwa i więcej tokenów, warto byłoby zrobić osobną tablicę
## Import zdjęć
- Będą importowane tylko nowe zdjęcią
- Będzie pokazywany komunikat o ilości importowanych zdjęć


### To do
- Login za pomocą login + password, przes formę + CSRF
- Linki Like Photo - musi być POST request
- stworzyć Application Testy
- zabrać część logiki z dislikePhoto do LikeService, zrobić metod toggleLike
- rate limiter: https://dev.to/mnishiguchi/rate-limiter-for-phoenix-app-3j2n#:~:text=2.0%22%7D%20%5D%20end-,Implement%20a%20plug,to_list()%20%7C%3E%20Enum.