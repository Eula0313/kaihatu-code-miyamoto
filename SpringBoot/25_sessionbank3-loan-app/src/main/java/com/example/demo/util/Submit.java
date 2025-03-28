package com.example.demo.util;

import org.springframework.ui.Model;

import java.net.InetAddress;
import java.net.UnknownHostException;

public class Submit {
    //出力データ設定
    static public void setPrint(Model model){
        // ホスト名を取得してモデルに追加
        try {
            String hostName = InetAddress.getLocalHost().getHostName();
            model.addAttribute("hostName", hostName);
        } catch (UnknownHostException e) {
            // エラー発生時のメッセージを追加
            model.addAttribute("hostName", "ホスト名取得に失敗しました: " + e.getMessage());
        }
    }
}
