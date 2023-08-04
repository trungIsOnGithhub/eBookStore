## Online Books and Magazine Store

> Developing and Testing with [XAMPP for Windows version 7.4.30](https://www.apachefriends.org/blog/new_xampp_20221001.html)

#### Update - Built myself a custom MVC framework(refrencing this book) for further learning in this project and probably to rewrite the codebase with my custom framework.
![Pro PHP MVC by Chris Pitt](bookcover.jpg)
#### Update APIs of this project being developed in [this repository](https://github.com/trungIsOnGithhub/eBookStore-api)

*:golf: Coursework project for "Web Programming" course **|** Semester 2022-1 **|** HCMUT*

>- *This site is made solely for purpose of learning and practicing, as there may still exists severals flaws that I am working on :grinning:*

>*As the codebase is under refactoring process, below is the tasks checklist*

<details markdown="1"><summary>Tasks Checklist</summary>
- [x] upload original code
- [x] implement MVC for landing page
- [ ] implement MVC for shoping page
- [ ] implement Wrapper
- [x] re-organize 'News' section
- [x] refactor 'Admin' section
- [x] redesign header and footer
</details>
-------------------------------
##### :paperclip: To go into details:

* **MVC pattern implemented for each webpage**
  - The original source code, written in PHP was really messy :scared:
  - Rewrite 'Model' part(served connecting to database) to Object-Oriented PHP
    - enhances readability although appears to be quite lengthy
* **Database Schemma Design**
  - Database schemma design was emphasized
    - normalized with [BCNF](https://de.wikipedia.org/wiki/Normalisierung_(Datenbank)#Boyce-Codd-Normalform_(BCNF))
    - essential attributes were all considered and included to table object
  - Soft delete applied for items with an option to physicaly delete
* **Additionality**
  - Lazy-loading image, SEO meta tag

-------------------------------------------------------
