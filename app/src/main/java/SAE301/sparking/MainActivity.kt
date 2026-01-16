package SAE301.sparking

import android.os.Bundle
import android.view.ViewGroup
import android.webkit.WebView
import android.webkit.WebViewClient
import androidx.activity.ComponentActivity
import androidx.activity.compose.setContent
import androidx.activity.enableEdgeToEdge
import androidx.compose.foundation.layout.fillMaxSize
import androidx.compose.foundation.layout.padding
import androidx.compose.material3.Scaffold
import androidx.compose.ui.Modifier
import androidx.compose.ui.viewinterop.AndroidView
import SAE301.sparking.ui.theme.SparkingTheme
import android.webkit.WebSettings
import androidx.compose.runtime.Composable

class MainActivity : ComponentActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        enableEdgeToEdge()
        setContent {
            SparkingTheme {
                Scaffold(modifier = Modifier.fillMaxSize()) { innerPadding ->
                    // On remplace "Greeting" par notre WebView
                    MyWebViewContent(modifier = Modifier.padding(innerPadding))
                }
            }
        }
    }
}



@Composable
fun MyWebViewContent(modifier: Modifier = Modifier) {
    // Ce composant permet d'utiliser une WebView (vue classique) dans Compose
    AndroidView(
        modifier = modifier.fillMaxSize(),
        factory = { context ->
            WebView(context).apply {
                layoutParams = ViewGroup.LayoutParams(
                    ViewGroup.LayoutParams.MATCH_PARENT,
                    ViewGroup.LayoutParams.MATCH_PARENT
                )

                // Configuration de la WebView
                webViewClient = WebViewClient() // Pour que les liens restent dans l'app
                settings.javaScriptEnabled = true // Active le JS pour ton site
                settings.domStorageEnabled = true // Utile si ton site utilise du stockage local
                // À ajouter impérativement pour que le JS puisse lire tes fichiers JSON
                settings.allowFileAccessFromFileURLs = true
                settings.allowUniversalAccessFromFileURLs = true
                settings.mixedContentMode = WebSettings.MIXED_CONTENT_ALWAYS_ALLOW

                // Charge ton fichier local situé dans le dossier assets
                loadUrl("file:///android_asset/SAE301_Finalee/src/vue/index.html")
            }
        }
    )
}