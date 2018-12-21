class unixfile {
  define file($file=$title, $mode=undef, $owner=undef, $group=undef, $source) {
    if !defined(Package[dos2unix]) {
      package { dos2unix:
        ensure => present
      }
    }
    file { $file:
      mode   => $mode,
      owner  => $owner,
      group  => $group,
      source => $source,
    }
    exec { "dos2unix $file":
      command   => "dos2unix $file",
      path      => "/usr/local/bin/:/bin/:/usr/bin/",
      require   => [Package["dos2unix"], File[$file]],
      logoutput => false,
      timeout   => 1800,
    }
  }
}
