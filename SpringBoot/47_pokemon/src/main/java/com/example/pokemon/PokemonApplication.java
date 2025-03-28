package com.example.pokemon;

import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;

@SpringBootApplication
public class PokemonApplication {
	/** 起動メソッド */
	public static void main(String[] args) {
		SpringApplication.run(PokemonApplication.class, args)
				.getBean(PokemonApplication.class);
	}
}
