set :application, "Dev Trends"

set :domain,      "util7.lhr1.digitalwindow.com" #uses ~/.ssh/config
set :deploy_to,   "/home/sites/trendsetter"

set :app_path,    "app"
set :use_composer, true
set :update_vendors, true
set :shared_files,      ["app/config/parameters.yml"]
set :shared_children,     [app_path + "/logs", web_path + "/uploads", "vendor"]

set :scm,         :git
set :branch,      "release-internal"
set :repository,    "file:///Users/mikepearce/sites/AWIN/trendsetter"
set :deploy_via,    :copy

set :user, "admin"
set :group, "apache"
set :use_sudo, false
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

default_run_options[:pty] = true

# Use local keys
set :ssh_options, { :forward_agent => true }

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

# Set the group correctly
after "deploy", :setup_group
task :setup_group do
  run "cd #{deploy_to}/current"
  run "setfacl -dR -m u:apache:rwx -m u:`whoami`:rwx #{deploy_to}/current/app/cache #{deploy_to}/current/app/logs"
  #run "setfacl -R -m u:apache:rwX -m u:`whoami`:rwX #{deploy_to}/current/app/cache #{deploy_to}/current/app/logs"
  run "php #{deploy_to}/current/app/console cache:clear --env=prod --no-debug"
  run "php #{deploy_to}/current/app/console assetic:dump --env=prod --no-debug"
end