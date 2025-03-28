package com.starbucks;

import lombok.RequiredArgsConstructor;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.*;

@Controller
@RequestMapping("/index")
@RequiredArgsConstructor
public class indexController {
    public String index() {
        return "index";
    }
}