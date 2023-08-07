# Hero Emergency App Backend
My graduation project was a first aid application called Hero.

Hero contains various features as:
* Clear step-by-step instructions for medical, pet and home emergency cases using video, audio and text.
* User can search via text or voice using case name or common symptoms or synonyms to find the emergency case in English or Arabic.
* A button to show all the emergency numbers. User can add a contact to the emergency numbers and call direct from the app.
* A button to locate nearest hospitals or nearest vets based on user's current location.
* User can chat with chatbot via text ot voice(Speech-To-Text) to identify the emergency case and provide the appropriate steps. Chatbot response also can be in the form of text or voice(TTS).


## Project's backend


Laravel is the backend technology that we use to build our application. Our backend project is implemented using the MVC pattern.

### Our backend contains 8 tables:
* Emergency number table
* Category table
* Medical
* Home
* Pets
* Arabic medical
* Arabic home
* Arabic pets

English and arabic categories tables are connected with the category table with One-to-Many relation, while the three english categories are connected with their equivalent arabic tables with One-to-One relation.

### Logic of the backend
* Storing english and arabic emergency cases in database. (case name, description, solution, image, video)
* Retrieving english and arabic emergency cases from database.
* Deleting english and arabic emergency cases.
* List all emergency cases.
* List specific case.

