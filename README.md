# slim3

Mini project qui consiste à mettre en place 2 interfaces:
=> interface admin qui permet à l'admin de se connecter, créer, modifier, supprimer ses articles aini que les images associées.
=> interface front qui recupère les articles par zone et un système d'envoie d'email


Kameran:
=> depus in rosu mesaje de eroare la pagina contact (ca in backend)
=> de testat pagina de contact (send mail, tradus mesaje in ro)
=> de testat pagina legislative (order by date desc)

PT mail: (ai angular deci ai npm daca nu trb sa instalezi si npm)

to install maildev: npm install -g maildev@1.0.0-rc2
to start maildev: maildev --ip 127.0.0.1
run those command lines inside slim3 folder

pt bug la pagina legislative dai copy la tot ce e in mig1.sql si dai paste in SQL la phpmyadmin

in pagina de cere oferta, campul Serviciul Solicitat  nu trb sa fie de tip select si o lista de servici ??

in pagina de contact si cere-oferta butonul Back pus in dreapta poate crea confuzie (parerea mea)
=> de analizat in dreapta sau stanga

Atentie cand faci un form, pt fiecare input, sa faci atentie ce pui la attr name, id, type

!!! pt git commit: 
git status 
git add <filename>
git commit -a -m "mesaj"
git push origin master


Thuy Dung:
=> de fixat bug cu image size la adaugare image
=> de fixat bug cu slug url
=> de facut sistem de newsletter


