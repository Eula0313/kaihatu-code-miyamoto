package com.starbucks.admin.service;

import org.springframework.stereotype.Service;
import org.springframework.web.multipart.MultipartFile;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;

@Service
public class ImageService {
    private final String UPLOAD_DIR = "src/main/resources/static/images/products/";

    public String uploadImage(MultipartFile file) {
        try {
            // 保存先パスを作成
            String fileName = System.currentTimeMillis() + "_" + file.getOriginalFilename();
            Path path = Paths.get(UPLOAD_DIR + fileName);

            // ファイルを保存
            Files.createDirectories(path.getParent());
            Files.write(path, file.getBytes());

            return "/images/products/" + fileName;
        } catch (IOException e) {
            throw new RuntimeException("画像のアップロードに失敗しました: " + e.getMessage(), e);
        }
    }
}
