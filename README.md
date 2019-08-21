# Casas Rurales Testing

## Requeriments

1. Git
2. Docker & Docker Compose
3. Make
4. PHP 7.2

## Topics

- Testing pyramid
- Inverted pyramid and refactor
- Good practices
- Test doubles with Prophesize (See: https://en.wikipedia.org/wiki/Test_double)
- Unit/Integration/E2E tests
- Do not repeat same test in your code (dependencies)
- PHPUnit and Coverage:
    - Command to generate the code coverage
    - How execute test and coverage for differents pyramid layers
    - Exclude files and parts of code to generate the coverage
- Costs of testing
- Tips and tricks

## Refactor: Code1, Code2 and Code3
    
In source folder (src) we have 3 subfolders:

- Code1: Legacy code, coupled code, no single responsability, ... (Integration tests).

- Code2: First refactor, dependency injection, more readable (Integration + Unit tests). 

- Code3: Small refactor about Code2, more encapsulation and replace implementations by interfaces (Integration + Unit tests)   
    
## Makefile

In Makefile you have some commands to setup the project and execute tests and coverage.


