# lotto-tester
С помощью данного приложения можно проверить, как смена карточек, при игре в лото, влияет на кол-во выигрышей.

### Возможности:
 * установление любого возможного кол-ва игроков
 * каждому игроку можно задать имя, кол-во карточек, а так же указать, должен ли игрок менять карточки после каждой игры, или всегда играть одними
 * в качестве набора карточек взят реально существующий набор, при желании можно определить собственный
 * можно задать как кол-во встреч, так и кол-во партий на каждой встречи
 * можно установить один из двух типов игр: "Короткое лотто" (выигрывает закрывший любой ряд) и "Лотто 3х3" (игра прекращается после закрытия третего ряда)
 
 
Для примера, ситуация может быть сконфигурирована следующим образом:
> Трое игроков (А, Б ,В), встречались трижды и каждый раз играли по 10 партий, при этом игрок А взял 5 карточек и не менял их, игрок Б взял 3 карточки и так же не менял их, игрок В взял 3 карточки и менял их после каждой игры.

В результате будет получена статистика, где будет видно, чья тактика была более успешной.


### Краткий итог
- Смена карточек не влияет на кол-во выигрышей. При игре 100 партий троих игроков, где один игрок меняет карточки, а двое не меняют, кол-во побед будет примерно равным (33/33/33).
- Есть прямая зависимость кол-во побед от кол-ва карточек. Карточек должно быть больше, чем у соперников. Для резкого увеличения процента побед, карточек должно быть вдвое больше, чем у соперников. При большом кол-ве игр (например 100) преимущество большого кол-ва карточек становится явным. Однако, в реальных условиях преимущество будет незначительным. Из 10 игр, можно проиграть все 10, имея больше карточек.

### TODO
- Проверить теорию: Вероятно, набор карточек с минимальным числом отсутствующих номеров, будет более выигрышным 
