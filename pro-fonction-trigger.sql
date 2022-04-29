--curseur explicit
declare
cursor product_curseur is select nameProduct, priceProduct from category where idProduct=1 for update;
name_product cat�gorie.nameProduct%type;
price product.priceProduct%type;
begin
open product_curseur;
loop
fetch product_curseur into price_product;
exit when (cathegorie_curseur%Notfound);
if v_price < 10 then
update product set priceProduct = price*15 where current of product_curseur;
end if;
end loop;
close product_curseur;
end;

select * from product;
--crsor implicites 
set serveroutput on
declare
nb_lignes number;
begin
delete from  product where Price = 10;
v_nb_lignes := SQL%ROWCOUNT;
dbms_output.put_line('nombre de products est'|| nb_lignes);
end;
select * from "product";


--procedure affichage nom et prenom  sans parametre avec curseur

create or replace procedure  member is
begin
 declare
 cursor c_member is
 select firstnameMember , emailMember from member;
 begin
 for v_guest in c_member loop
dbms_output.put_line('name : '||v_guest.firstnameMember||
' email : '|| v_guest.emailMember);
end loop ;
end;
end;
set serveroutput on
call member();



--fonction convertion de prix de chambre de euro au tnd
create or replace function prix_en_tnd (prix in number )
return number
is
begin 
declare 
taux constant number := 0.3;
begin
return prix * taux ;
end;
end;
--execution de fonction dans un requette
select prix_en_euro(priceProduct) from product ;
select * from product ;


SELECT *from product;