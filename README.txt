What is this?
================================================================================
com_todo is a demo component for the FOF framework. Its purpose is to
demonstrate how you can create a small, but fully functional, component for
Joomla! 2.5+ with minimal code.

Who is it for?
================================================================================
Developers who are interested in starting to learn how FOF works and use it in
their projects.

Can I have an installable package, pretty please?
================================================================================
Definitely! First make sure that you have a dev environment with PHP CLI, PEAR
and Phing installed. If you have no idea what Phing is and how to install it on
your machine, take a look at Phing's page:
http://www.phing.info/trac/wiki/Users/Installation

Now, you will need to check out both the FOF and To-Do projects:

git clone git://git.assembla.com/fof.git
git clone git://git.assembla.com/to-do-fof.git

Right now the FOF working copy is in the factory default state and FOF can not
be installed just yet. Let's make sure we get it into an installable state:

cd fof/build
phing
cd ..

And then let's build com_todo itself:

cd to-do-fof/build
phing
cd ..

You will find a com_todo-****.zip file inside the to-do-fof/release directory.
Awesome! You can now install it on your Joomla! 2.5 or later web site. Yes,
com_todo is for Joomla! 2.5+ only even though FOF currently also supports
Joomla! 1.5. Sorry, but Joomla! 1.5 is practically dead!

I found a bug / have a suggestion / whatever.
================================================================================
Cool! You can fork the Git repo, implement your bug fix / change / whatnot and
send back a merge request. We'll review the code and discuss it with you. Most
of the changes requested are implemented, especially if they come as merge
requests :)

Can I use this in my commercial projects?
================================================================================
Sure! com_todo and FOF are both licensed under the GNU GPL version 3 or later.
As long as you satisfy the requirements of the GPL you can freely use it.

How can I be sure FOF won't go away?
================================================================================
The only way for me to make a living is selling subscriptions to my extensions.
They all use FOF. If I were to discontinue FOF I would have to reinvent the
wheel or go out of business. I neither fancy reinventing the wheel or dying
poor, so there you have it.

Your design sucks!
================================================================================
Definitely. Let me put it this way. Would you ask your car mechanic to paint your
portrait?

Your code sucks!
================================================================================
OK, let me put it this way. Would you rather have a pair of old shoes today or a
teleporting machine in some unspecified day in the future if all you want is to
go to the grocery store to buy some food so as not to starve to death?

Speaking of grocery stores, can I have cheese with it?
================================================================================
I believe so, but your keyboard would get greasy and the keys would get all
sticky and malfunctioning. This is a situation most developers try to avoid.

What's the Answer to the Ultimate Question of Life, the Universe, and Everything?
================================================================================
42

Do you take freelancing projects?
================================================================================
Not any more, but thanks for asking!