config = ENV.fetch("RAILS_RESQUE_REDIS", "127.0.0.1:6379")
Resque.redis = config
# This will make the scheduler tabs show up.
require 'resque-scheduler'
require 'resque/scheduler/server'
