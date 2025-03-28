package com.starbucks.config;

import com.starbucks.admin.service.AdminUserDetailsService;
import com.starbucks.shop.service.ShopUserDetailsService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.core.annotation.Order;
import org.springframework.security.authentication.AuthenticationManager;
import org.springframework.security.config.annotation.authentication.configuration.AuthenticationConfiguration;
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.crypto.bcrypt.BCryptPasswordEncoder;
import org.springframework.security.crypto.password.PasswordEncoder;
import org.springframework.security.web.SecurityFilterChain;

@Configuration
public class SecurityConfig {
    @Autowired
    private AdminUserDetailsService adminUserDetailsService;
    @Autowired
    private ShopUserDetailsService shopUserDetailsService;

    @Bean
    @Order(1)
    public SecurityFilterChain adminSecurityFilterChain(HttpSecurity http) throws Exception {
        http
                .securityMatcher("/admin/**")
                .authorizeHttpRequests(auth -> auth
                        .requestMatchers("/admin/login").permitAll()
                        .requestMatchers("/admin/**").hasRole("ADMIN")
                        .anyRequest().authenticated()
                )
                .formLogin(form -> form
                        .loginPage("/admin/login")
                        .loginProcessingUrl("/admin/login")
                        .defaultSuccessUrl("/admin/dashboard", true)
                        .failureUrl("/admin/login?error")
                        .permitAll()
                )
                .logout(logout -> logout
                        .logoutUrl("/admin/logout")
                        .logoutSuccessUrl("/admin/login")
                        .permitAll()
                )
                .exceptionHandling(ex -> ex
                        .accessDeniedHandler((request, response, accessDeniedException) ->
                                response.sendRedirect("/admin/login"))
                        .authenticationEntryPoint((request, response, authException) ->
                                response.sendRedirect("/admin/login"))
                )
                .userDetailsService(adminUserDetailsService);
        return http.build();
    }

    @Bean
    @Order(2)
    public SecurityFilterChain shopSecurityFilterChain(HttpSecurity http) throws Exception {
        http
                .securityMatcher("/shop/**")
                .authorizeHttpRequests(auth -> auth
                        .requestMatchers("/", "/css/**", "/images/**", "/js/**", "/shop/products", "/shop/login").permitAll()
                        .requestMatchers("/shop/products/**").hasRole("USER")
                        .anyRequest().authenticated()
                )
                .formLogin(form -> form
                        .loginPage("/shop/login")
                        .loginProcessingUrl("/shop/login")
                        .defaultSuccessUrl("/shop/products", true)
                        .failureUrl("/shop/login?error")
                        .permitAll()
                )
                .logout(logout -> logout
                        .logoutUrl("/shop/logout")
                        .logoutSuccessUrl("/shop/products")
                        .permitAll()
                )
                .exceptionHandling(ex -> ex
                        .accessDeniedHandler((request, response, accessDeniedException) ->
                                response.sendRedirect("/shop/login"))
                        .authenticationEntryPoint((request, response, authException) ->
                                response.sendRedirect("/shop/login"))
                )
                .userDetailsService(shopUserDetailsService);
        return http.build();
    }

    @Bean
    public PasswordEncoder passwordEncoder() {
        return new BCryptPasswordEncoder();
    }

    @Bean
    public AuthenticationManager authenticationManager(AuthenticationConfiguration authConfig) throws Exception {
        return authConfig.getAuthenticationManager();
    }
}