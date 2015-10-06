<?php

use Composer\Script\Event;

class DrupalSkeletonInstaller {

  public static function install(Event $event) {

    $human_name = '';
    while(empty(trim($human_name))) {
      $human_name = readline('Human Name: ');
    }
    $human_name = trim($human_name);

    $machine_readable = strtolower($human_name);
    $machine_readable = preg_replace('@[^a-z0-9_]+@','_',$machine_readable);

    $machine_name = readline('Machine Name (' . $machine_readable . '): ');
    if (empty(trim($machine_name))) {
      $machine_name = $machine_readable;
    }

    print PHP_EOL;
    print '-----' . PHP_EOL;
    print 'Human name: ' . $human_name . PHP_EOL;
    print 'Machine Name: ' . $machine_name . PHP_EOL;
    print '-----' . PHP_EOL;
    print PHP_EOL;

    $confirm = '';
    while(trim($confirm) != "Y") {
      $confirm = readline('Type Y to continue: ');
    }

    $base_path = '.';

    $replacements = array(
      '__PROJECT_MACHINE_NAME__' => $machine_name,
      '__PROJECT_HUMAN_NAME__' => $human_name,
      '__RANDOM_HASH_SALT__' => DrupalSkeletonInstaller::generateRandomString(64),
    );

    unlink('README.md');
    rename('PROJECT-README.md', 'README.md');
    DrupalSkeletonInstaller::updateDir($base_path, $replacements, $machine_name);

    print PHP_EOL;
    print PHP_EOL;
    print 'Congratulations!' . PHP_EOL;
    print 'You now initialized a new project from the Skeleton!' . PHP_EOL;
    print PHP_EOL;
    print 'To start developing:' . PHP_EOL;
    print PHP_EOL;
    print 'First commit your project:' . PHP_EOL;
    print ' - git init' . PHP_EOL;
    print ' - git remote add origin <repository-url>' . PHP_EOL;
    print ' - git add .' . PHP_EOL;
    print ' - git commit -m "Initial Commit"' . PHP_EOL;
    print ' - git branch development' . PHP_EOL;
    print ' - git branch staging' . PHP_EOL;
    print ' - git push origin development' . PHP_EOL;
    print ' - git push origin staging' . PHP_EOL;
    print ' - git push origin master' . PHP_EOL;
    print PHP_EOL;
    print 'Create a new database for this Drupal site.' . PHP_EOL;
    print 'Install the Drupal environment:' . PHP_EOL;
    print ' - cd web' . PHP_EOL;
    print ' - nano sites/default/settings.local.php (enter the database details)' . PHP_EOL;
    print ' - ./../vendor/bin/drush site-install ' . $machine_name . ' --yes' . PHP_EOL;
    print ' - ./../vendor/bin/drush config-import --yes' . PHP_EOL;
    print PHP_EOL;
    print 'Now your site is ready!' . PHP_EOL;
    print PHP_EOL;
    print PHP_EOL;
  }

  public static function updateDir($main, $replacements, $machine_name){
    $dirHandle = opendir($main);
    while($file = readdir($dirHandle)) {
      $curpath = $main . '/' . $file;

      // Skip .git and vendor.
      if ($file == '.git' || $curpath == './vendor') {
        continue;
      }

      $contains_machine_name = strpos($main . '/' . $file, '__PROJECT_MACHINE_NAME__');
      if ($contains_machine_name !== FALSE) {
        $new_path = str_replace('__PROJECT_MACHINE_NAME__', $machine_name, $curpath);
        rename($curpath, $new_path);
        print 'Renamed ' . $curpath . ' to ' . $new_path . PHP_EOL;
        $curpath = $new_path;
      }

      if(is_dir($curpath) && $file != '.' && $file != '..'){
        DrupalSkeletonInstaller::updateDir($curpath, $replacements, $machine_name);
      }
      else{
        $old_contents = file_get_contents($curpath);
        $new_contents = str_replace(array_keys($replacements), array_values($replacements), $old_contents);
        if ($new_contents != $old_contents) {
          file_put_contents($curpath, $new_contents);
          print 'Updated File: ' . $curpath . PHP_EOL;
        }
      }
    }
  }

  public static function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
}
