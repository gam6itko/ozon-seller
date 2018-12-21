$phpV = "php71w"
$pp_files_dir="/vagrant/vagrant/manifests/files"
$pp_dir="/vagrant/vagrant/manifests"
$log_dir="/var/log/shared_logs"

class sandbox {
  file { [$log_dir]:
    ensure => directory,
    owner  => root,
    group  => root,
    mode   => 0777,
  }

  package { mc:
    ensure => latest
  }
}

class repos {
  package { "epel-release":
    provider => "rpm",
    ensure   => latest,
    source   => "http://dl.iuscommunity.org/pub/ius/stable/CentOS/6/i386/epel-release-6-5.noarch.rpm",
  }
  package { "ius-release":
    provider => "rpm",
    ensure   => latest,
    source   => "https://dl.iuscommunity.org/pub/ius/stable/CentOS/6/i386/ius-release-1.0-14.ius.centos6.noarch.rpm",
    require  => Package["epel-release"],
  }
  package { webtatic-release:
    provider => "rpm",
    ensure   => present,
    source   => "https://mirror.webtatic.com/yum/el6/latest.rpm"
  }
}

class composer {
  file { "/var/cache/composer":
    ensure => "directory",
    mode   => 777,
    owner  => root,
    group  => root
  }
  file { "/var/cache/composer/repo":
    ensure => "directory",
    mode   => 777,
    owner  => root,
    group  => root
  }
  file { "/var/cache/composer/files":
    ensure => "directory",
    mode   => 777,
    owner  => root,
    group  => root
  }

  exec { "composer-setup":
    environment  => ["COMPOSER_HOME=/tmp/composer"],
    command      => "curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer",
    path         => "/usr/local/bin/:/bin/:/usr/bin/",
    logoutput    => true,
    timeout      => 1800,
    require      => [File['/var/cache/composer'], Package["${phpV}-cli"]],
    creates      => "/bin/composer"
  }
}

class php {
  package { ["${phpV}-cli", "${phpV}-mbstring", "${phpV}-soap"]:
    ensure  => latest,
  }
}

class xdebug {
  package { "${phpV}-pecl-xdebug":
    ensure  => latest,
    require => [Package["${phpV}-cli"]],
  }
}


node default {
  include sandbox
  include repos
  include php
  include xdebug
  include composer
}
