# Pay Per Use

This project aims into proving that achieving rich entities model can be easy and doesn't demand too much customization from Symfony or Doctrine.

So here I remove the majority of the logic from the services, and move into the entities. In a project like this, even a service that internally does some processing or calls an external endpoint, can be considered important enough to become a domain entity.

Services in this context, are nothing but connectors, proxies, or just hold things together to promote decoupling between two entities.

## Use Case Example
A user install an app to convert photos into 3D models. This app requests an API for that user:

  1. Create a User with an initial amount of credits;
  1. User may change its full name;
  1. User requests a Render;
     1. The User domain checks if it can afford that service request;
     1. The User domain requests the current Render to process the pack of images;

## Entities
  * User
    - \+changeFullName(string)
    - \+requestRender(Render, zipfile)
    - fullName
    - email*
    - Credits
      - \+ canAfford(int)
      - \+ add(int)
      - \+ deduct(int)
      - amount
  * Render
    - \+getQuotation(): usageCost
    - \+processRequest(zipfile): usageCost
    - name
    - description
    - usageCost
    - endpoint

## Initial E2E Test Cases
  1. Create User;
  2. Update full name;
  3. Render an image pack;


