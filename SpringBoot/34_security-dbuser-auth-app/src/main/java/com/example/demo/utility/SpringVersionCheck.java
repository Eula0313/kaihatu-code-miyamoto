package com.example.demo.utility;

import org.springframework.boot.SpringBootVersion;
import org.springframework.core.SpringVersion;
import org.springframework.security.core.SpringSecurityCoreVersion;

public class SpringVersionCheck {
    public static void main(String[] args) {
        // Spring Frameworkのバージョン
        String springVersion = SpringVersion.getVersion();
        System.out.println("Spring Frameworkのバージョン: " + springVersion);
        // Spring Bootのバージョン
        String bootVersion = SpringBootVersion.getVersion();
        System.out.println("Spring Bootのバージョン: " + bootVersion);
        // Spring Securityのバージョン
        String securityVersion = SpringSecurityCoreVersion.getVersion();
        System.out.println("Spring Securityのバージョン: " + securityVersion);
    }
}
