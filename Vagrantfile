VAGRANTFILE_API_VERSION = "2"
VM_NAME = 'ozon-seller.vagrant'

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.box = "CentOS-6.7-x86_64"

  config.vm.box_url = "http://developer.nrel.gov/downloads/vagrant-boxes/CentOS-6.7-x86_64-v20151108.box"

  config.vm.provider :virtualbox do |vb|
    vb.name = VM_NAME
  end

  config.vm.provision :puppet do |puppet|
    puppet.manifests_path = "vagrant/manifests"
    puppet.module_path = "vagrant/manifests/modules"
    puppet.manifest_file  = "site.pp"
  end
end