# Test 3 Notes - Karl Amaya

## Setup

1. find the db.sql and the data.sql files in the src/Database/ directory
2. run the db.sql and data.sql files
3. edit the $BASE_PATH variable found in the pages/includes/helpers.php file
    - this is important as it helps to get resources such as css, images, and js, files.
    - if u are using wamp or mamp, the path would more than likely be "/shoppingCartApp" as that would be the root folder for the app when placed in htdocs.
4. run composer install
5. run the server either through the run.sh script or through your preferred web server.

## Pages
1. /?page=login - this is the user login page
2. /?page=signup - this is the user signup page
3. / - this is the store page, you can also use /?page=store
4. /?page=cart - this page will show you your cart
5. /?page=checkout - this page will show you the checkout form
6. /?page=reciept - this page will show you your receipt

## API
- files in the /api/ folder are used to offer an API for performing ajax requests.

## Other notes
- I have tried to recreate a small MVC like structure in my app. 
    that is why I have lot of files.

## Use Case
1. User signs in
2. User creates account
3. User signs out
4. User views store
5. User adds Items to cart
6. User cannot add Item to cart if item already exists
7. User views cart
8. User removes cart items
9. User edits the quantity of the item in their cart - limit of 1 min 100 max
10. User goes to checkout
11. User views a summary of their cart
13. User adds a promo code - code validation, for testing view promocodes table to for useable codes.
13. User submits Billing information
14. User views Receipt pdf


## Things I couldn't figure out or do
- On my machine php runs really slow, I'm not sure why, and I couldn't test to see if its slow
    on other machines. If its slow on your machine as well, then I probably did something wrong.
- the slowness caused me to have to remove some functionality I had and do some weird workarounds in the front end.
- I saw I misspelled Receipt wrong all over the place a bit too late.