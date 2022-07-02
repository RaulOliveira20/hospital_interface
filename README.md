# hospital_interface

This is a project of a hospital interface with made-up data.

The main page is only able to access and view data, while the staff and admin pages possess more buttons where they can also modify and add/delete information.

The test.php file is where the connection to the database is made and where data is retrieved and displayed.

Anyone can access the main page, since it requires no login of any sort. All of the buttons are searches only and it looks like this:

![hi_user](https://user-images.githubusercontent.com/36230040/176589032-1200299f-dd49-4466-b6ba-d1ca8a95d05e.png)

Each of the buttons show different data stored in the database.

This is what shows if the "Show employees" button is clicked:

![hi_see_employees](https://user-images.githubusercontent.com/36230040/176589880-568f43cd-9e2c-480e-ab4c-ee2ebc31d179.png)

The specialty area only has a value for the doctors and it is null for the others.

In this table, the names or the titles can be filtered in the search box. Here is the table displayed with "doctor" in the box:

![hi_see_employees_doctor](https://user-images.githubusercontent.com/36230040/176590291-1838d2fb-f508-436c-8356-fa21a7950cfc.png)

Other tables of data also have a search box, which usually allows the user to filter one or two specific columns.

Most of the buttons that just show data, show the table right away, but in some cases, certain inputs are needed first to get specific information.

If the button "Show employees working" is clicked, it gives the user the option to choose a day of the week and an hour:

![hi_see_employees_working_certain_time](https://user-images.githubusercontent.com/36230040/177008140-93215f4a-7e4b-4e93-8726-a9782a3c7249.png)

After selecting a day and an hour, and clicking the "submit" button, the table displayed shows all employees working at that day and at that hour. Here's a table showing all employees working at 12PM on Thursday:

![hi_see_employees_working_thursday_12pm](https://user-images.githubusercontent.com/36230040/177008223-39f248ee-93d4-4e24-867d-5e20f3d9c3a3.png)

