# vermillion-server

Don't just manage your server, command it.  Allow authenticated clients to send requests to a web application which will perform command line tasks, such as git-pull or even [create a whole new web project](https://gist.github.com/aapis/f06e7381b7b1770757acdad81d50dd90) for you.

## Web

As this is a `Symfony 3` application, a web interface will be available to make changes directly to your projects.  The app maintains an internal list of project directories and does not touch any directory it is not permitted to.

## CLI

Perform the same actions you would through a client locally on the server.