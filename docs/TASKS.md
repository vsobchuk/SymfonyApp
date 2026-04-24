## Zadanie
SymfonyApp to aplikacja, która pozwala użytkownikom na dzielenie się swoimi zdjęciami.
Jest we wczesnym etapie rozwoju i zawiera kilka podstawowych funkcjonalności.
Są to:
- Wyświetlanie galerii zdjęć na stronie głównej. Każdy kafelek zawiera podstawowe informacje oraz ilość polubień.
- Like/unlike zdjęć.
- Logowanie za pomocą tokenu oraz możliwość wylogowania.
- Wyświetlenie profilu.
 
### Zadanie 1 - zadbaj o jakość kodu oraz rozwiązań w projekcie SymfonyApp.
Znajdź błędy, a następnie nanieś co najmniej 5 poprawek, które uważasz za najbardziej istotne. Niedoskonałości jest więcej, dlatego możesz zasugerować co byś jeszcze zmienił, ale zaprezentowanie tego w kodzie jest mile widziane.
Upewnij się, że projekt ma dobre fundamenty pod dalszy rozwój - struktura kodu musi być łatwa do zrozumienia dla nowych programistów.
 
### Zadanie 2 - Dodaj funkcjonalność importu zdjęć do SymfonyApp z PhoenixApi.
PhoenixApi to aplikacja, która przechowuje zdjęcia z innych aplikacji partnerskich, z których korzystają użytkownicy SymfonyApp. Wystawiony jest endpoint, za pomocą którego można pobrać zdjęcia używając tokenu dostępu.
 
W aplikacji SymfonyApp należy dać użytkownikom możliwość ręcznego wpisania tokenu dostępu do PhoenixApi (w profilu użytkownika). Token powinien zostać zapisany w bazie danych.
Następnie, po naciśnięciu przycisku "Importuj zdjęcia", zdjęcia z PhoenixApi powinny zostać zaimportowane do SymfonyApp jako zdjęcia tego użytkownika. 
W przypadku błędnego tokenu, należy wyświetlić odpowiedni komunikat.
 
### Zadanie 3 - Filtrowanie zdjęć na stronie głównej.
Użytkownicy SymfonyApp muszą mieć możliwość filtrowania zdjęć po następujących polach:
- location
- camera
- description
- taken_at
- username
 
### Zadanie 4 - Zaimplementuj rate-limiting w aplikacji PhoenixApi.
Poszczególny użytkownik powinien móc importować swoje zdjęcia z PhoenixApi do SymfonyApp maksymalnie 5 razy na 10 minut. Oprócz tego, liczba wszystkich importów zdjęć może wynosić maksymalnie 1000 na godzinę. Jeśli znasz Elixira, spróbuj wykorzystać do tego OTP.
