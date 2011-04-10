README
======

A Zend Framework resource for Doctrine ORM 2.0

Using The CLI Scripts
=====================

All command line scripts live in the ``bin`` folder::

    bin/foo "Hello World"

so if you see ``bin/foo`` it  means run that as a command in
your terminal.

Both  the Zend  and Doctrine  command line  tools are  found
here, you can run them as follows::

    bin/doctrine
    bin/zf.sh

Configuration
=============

This system  uses a  plain PHP  configuration file.  The INI
version  just gets  converted  to a  regular  old PHP  array
anyway, so this is a little  faster and as long as you don't
mind editing plain PHP syntax this reduces overhead.

The config file is::

    application/configs/config.php

You  will need  to  edit this  file and  add  or change  the
database, username and password.

Installation
============

You will,  however, need  to generate  the database.  You do
this with the doctrine command line tool.

The following commands will generate the database::

    bin/doctrine orm:schema-tool:create
    bin/doctrine dbal:import test_data.sql

This is a pretty standard  Zend Framework application so not
much installation is really required, just set up your vhost
like normal for any Zend Framework application.

Loading The Doctrine System
===========================

There are two new files to share some of the work::

    application/common.php
    application/doctrine/common.php

``application/common.php``  contains   most  of   the  stuff
you   would    find   in   ``public/index.php``    such   as
``APPLICATION_PATH`` etc

``application/doctrine/common.php``  contains  most  of  the
stuff you would find in the resource plugin and command line
scripts,  basically it  creates an  entity manager  from the
Zend configuration

In  order  to integrate  Doctrine  with  Zend we  must  make
Doctrine  aware of  the Zend  application, meaning,  roughly
that we  need to  tell doctrine  where the  Zend Application
config file is

Also we need to integrate both  the command line and the web
framework so we end up repeating this integration twice.

Encapsulating the  common code  like this helps  keep things
aligned with the DRY (Don't Repeat Yourself) principle

The Doctrine Resource Plugin
============================

The heart of this system is the Doctrine 2 resource plugin.

This plugin is loaded from the config file with the lines::

    $zfConfigArray['pluginPaths']['My_Resource'] = APPLICATION_PATH . '/resources';
    $zfConfigArray['resources']['doctrine'][] = '';

The first of  which tells ZF where to  find resource plugins
and the second of which actually triggers the loading of the
plugin named doctrine

The actual  file that does the  work to set up  doctrine for
use in the web framework is::

    application/resources/Doctrine.php

It basically just includes::

    application/doctrine/common.php

and  then places  the Doctrine  entity manager  in the  Zend
registry for easy and consistent access later.

The Doctrine Entities
=====================

The doctrine entities live in the::

    application/doctrine/Entities

folder.  You  can change  this  if  you  like, maybe  to  be
per  module or  something.  This too  is  a fairly  standard
Doctrine ORM  layout, so  all your  models will  actually be
called "entities" and  they will have a  namespace prefix of
``\Entities``.

All   my    entities   extend    a   base    entity   called
``AbstractEntity``. The  main purpose  of ``AbstractEntity``
is to make  work faster by providing  common automatic stuff
so you don't have to write things like getters and setters -
at least, not immediately.

Magic and  automation always  has a price  and in  this case
it's  performance that  suffers, so  theoretically you  will
write  your  own fully  finished  out  models with  explicit
getters  and setters,  but for  now  you can  just code  the
features you want and worry about optimization later.

The Example Blog
================

You should be able to see the example blog app at::

    http://yourhost.com/blog

There are a couple example models and a handful of test data
for you to play with. The example is just a simple blog
but hopefully it illustrates the idea well enough.

The Models
==========

The models live in::

    application/doctrine/Entities/Blog

and there are two of them::

    application/doctrine/Entities/Blog/Entry.php
    application/doctrine/Entities/Blog/Comment.php

These  are just  plain old  doctrine models  in regular  PHP
using  the docblock  annotations to  configure the  doctrine
specific settings.

You will notice  that, as mentioned in  the previous section
each  of these  models  extends  ``AbstractEntity`` and  the
very  astute observer  will  notice that  they  both use  an
``EntityRepository`` that extends ``PaginatedRepository``

The Custom Repository
---------------------

This file::

    application/doctrine/Entities/PaginatedRepository

basically  provides pagination,  though repositories  can be
used for just about anything. I figured this would work well
to again help DRY things up a little in terms of pagination.

The repository manager  is a handy feature  of doctrine that
lets  you gather  sets of  large and  complex queries  under
easily remembered method names.

The Controller
==============

This file::

    application/controllers/BlogController.php

Contains the standard RESTful style CRUD actions::

    index
    edit
    show
    create
    update
    delete

This is  probably the primary integration  point of interest
if you want to use something as a reference to start writing
your own models from this example app.

This is what actually uses the Doctrine ORM from within Zend
Framework, so  you can  see how  to both  get access  to the
database, and there are numerous examples of how to run most
of the main  query types available to you from  a Doctrine 2
entity manager.

The Views
=========

Not much special here, it's all just standard Zend Framework
based views using a default layout and a couple partials for
the pagination

Files, as usual, live in::

    application/views/scripts/blog

The Form
========

The blog entry edit form::

    application/forms/BlogEntry.php

has  one primary  interesting modification  - it  pulls form
repopulation values from  a Doctrine entity if  an entity is
given, e.g. on edit

It also isolates the actual  entity specific data from other
form elements like the submit  button and CSRF hash. This is
mostly  to  help  ensure  that extra  garbage  data  is  not
accidentally saved

The Custom DateTime Form Element
--------------------------------

So now that we're familiar with the ``library_fork`` it will
come as no surprise that there is a custom Zend_Form_Element
living here::

    application/library_fork/Zend/Form/Element/DateTime.php
    application/library_fork/Zend/View/Helper/FormDateTime.php

Doctrine uses a literal PHP DateTime object for its datetime
columns, and this poses problems  when attempting to read or
write  to  it  when  dealing with  strings,  like  from  the
database or from a user input form.

The main purpose of this  custom element is to make handling
of DateTime fields transparent, to help keep things DRY

The Custom Fork
---------------

So now comes time to explain what the::

    application/library_fork
    
directory is all about.

This  just makes  it easy  and simple  to override  the Zend
classes without actually modifying the core Zend code itself
and without resorting to using ones own namespace.

If for example  one of the Zend classes has  a half finished
feature  or an  option that  exists but  is not  technically
available to  be used in the  code (as I have  seen numerous
times), then a fixed and or patched version of the class can
be dropped into the  mirror location in ``library_fork`` and
the  forked  version  will  be used  in  preference  to  the
original.

Then at  some other point  in the  future, you may,  at your
leisure, submit a patch to Zend... or not :P

I originally started this to patch ``Zend_Form_Element`` but
realized  that there  are other  problems. This  is still  a
handy setup to make use of though

Where To Go From Here
=====================

Finish the CRUD  for Comments as a  learning exercise, using
the existing blog controller and views as a reference.

Fix any  of the numerous  bugs you are likely  to encounter,
such as not being able to delete entries that have comments,
due to foreign key constraints.

Write  some entities  of your  own using  the existing  Blog
models views and controllers as a reference.

Learn  more  about  both  Doctrine  and  Zend  Framework  in
general, but specifically about Doctrine.

* Associations
* Using the Entity Manager
* DQL Queries

Also read through the code, there's actually not that much and it
should give you a good place to start if you've never integrated
Doctrine with Zend Framework before.
