<?php
if ($_SESSION['loggedin'] == true) {
    Style::block_begin("Server Load");
    if (strtoupper(substr(PHP_OS, 0, 3)) == "WIN") {
        // Get total physical memory (this is in bytes)
        $cmd = "wmic ComputerSystem get TotalPhysicalMemory";
        @exec($cmd, $outputTotalPhysicalMemory);
        // Get free physical memory (this is in kibibytes!)
        $cmd = "wmic OS get FreePhysicalMemory";
        @exec($cmd, $outputFreePhysicalMemory);
        if ($outputTotalPhysicalMemory && $outputFreePhysicalMemory) {
            // Find total value
            foreach ($outputTotalPhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memoryTotal = $line;
                    break;
                }
            }
            // Find free value
            foreach ($outputFreePhysicalMemory as $line) {
                if ($line && preg_match("/^[0-9]+\$/", $line)) {
                    $memoryFree = $line;
                    $memoryFree *= 1024; // convert from kibibytes to bytes
                    break;
                }
            }
        }
        $memtotal = mksize($memoryFree);
        $memused = mksize($memoryTotal);

        $users = ["none"];
        $loadnow = "";
        $load5 = "";
        $load15 = "";
        $operatingsystem = "";
        $up = "";

    } else {
        // Users and load information
        $reguptime = exec("uptime");
        if ($reguptime) {
            if (preg_match("/up (.*), *(\d) (users?), .*: (.*), (.*), (.*)/", $reguptime, $uptime)) {
                $up = preg_replace("!(\d\d):(\d\d)!", '\1h\2m', $uptime[1]);
                $users[0] = $uptime[2];
                $users[1] = $uptime[3];
                $loadnow = $uptime[4];
                $load5 = $uptime[5];
                $load15 = $uptime[6];
            }
        } else {
            $up = "--";
            $users[0] = "NA";
            $users[1] = "--";
            $loadnow = "NA";
            $load5 = "--";
            $load15 = "--";
        }

        // Operating system
        $temp = file_get_contents("/proc/version");
        if ($temp) {
            $osarray = explode(" ", $temp);
            $distros = [
                "Gentoo", "/etc/gentoo-release",
                "Fedora Core", "/etc/fedora-release",
                "Slackware", "/etc/slackware-version",
                "Cobalt", "/etc/cobalt-release",
                "Debian", "/etc/debian_version",
                "Mandrake", "/etc/mandrake-release",
                "Mandrake", "/etc/mandrakelinux-release",
                "Yellow Dog", "/etc/yellowdog-release",
                "Red Hat", "/etc/redhat-release",
                "Arch Linux", "/etc/arch-release",
            ];

            $distro = "";
            if (file_exists("/etc/lsb-release")) {
                $lsb = file_get_contents("/etc/lsb-release");
                preg_match('!DISTRIB_DESCRIPTION="(.*)"!', $lsb, $distro);
                $distro = $distro[1];
            } else {
                do {
                    if (file_exists($distros[1])) {
                        $distro = file_get_contents($distros[1]);
                        $distro = "$distros[0] " . preg_replace("/[^0-9]*([0-9.]+)[^0-9.]{0,1}.*/", "\\1", $distro);
                        break;
                    }
                    array_shift($distros);
                    array_shift($distros);
                } while (count($distros));
            }

            if (!$distro) {
                $distro = "Unknown Distro";
            }

            $operatingsystem = "$distro ($osarray[0] $osarray[2])";
        } else {
            $operatingsystem = "(N/A)";
        }

        // RAM usage
        $meminfo = @file_get_contents("/proc/meminfo");
        preg_match("!^MemTotal:\s*(.*) kB!m", $meminfo, $memtotal);
        $memtotal = $memtotal[1] * 1024;
        preg_match("!^MemFree:\s*(.*) kB!m", $meminfo, $memfree);
        $memfree = $memfree[1] * 1024;
        preg_match("!^Buffers:\s*(.*) kB!m", $meminfo, $buffers);
        $buffers = $buffers[1] * 1024;
        preg_match("!^Cached:\s*(.*) kB!m", $meminfo, $cached);
        $cached = $cached[1] * 1024;

        $memused = mksize($memtotal - $memfree - $buffers - $cached);
        $memtotal = mksize($memtotal);
    }
    ?>
    <ul class="list-unstyled">
    <li> Current Users: <strong> <?php echo $users['0']; ?> </strong></li>
    <li> Current Load: <strong> <?php echo $loadnow; ?> </strong></li>
    <li> Load 5 mins ago: <strong> <?php echo $load5; ?> </strong></li>
    <li> Load 15 mins ago: <strong> <?php echo $load15; ?> </strong></li>
    <br/>
    <li> OS: <strong> <?php echo $operatingsystem; ?> </strong></li>
    <li> RAM : <strong> Used: <?php echo $memused; ?> / Free: <?php echo $memtotal; ?> </strong></li>
    <li> Uptime: <strong> <?php echo $up; ?> </strong></li>
    </ul>
    <?php
    Style::block_end();
}