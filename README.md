implementation 'com.googlecode.tesseract-android:tessearct:3.03'
implementation 'com.rmtheis:tess-two:9.0.0'

<uses-permission android:name="android.permission.READ_EXTERNAL_STORAGE" />
<uses-permission android:name="android.permission.WRITE_EXTERNAL_STORAGE" />

Intent intent = new Intent(Intent.ACTION_PICK, MediaStore.Images.Media.EXTERNAL_CONTENT_URI);
startActivityForResult(intent, PICK_IMAGE_REQUEST);

@Override
protected void onActivityResult(int requestCode, int resultCode, Intent data) {
    super.onActivityResult(requestCode, resultCode, data);
    if (requestCode == PICK_IMAGE_REQUEST && resultCode == RESULT_OK && data != null && data.getData() != null) {
        Uri imageUri = data.getData();
        // Process the selected image
        processImage(imageUri);
    }
}

Process the image and perform OCR:

Create a new method, processImage(Uri imageUri), to perform image processing and OCR.

Convert the image to a bitmap:

Bitmap bitmap = MediaStore.Images.Media.getBitmap(getContentResolver(), imageUri);

Initialize the OCR engine and set the language:
TessBaseAPI tessBaseAPI = new TessBaseAPI();
tessBaseAPI.init(getFilesDir().getAbsolutePath(), "eng");

Set the bitmap image as the input to the OCR engine:
tessBaseAPI.setImage(bitmap);

Retrieve the recognized text:
String recognizedText = tessBaseAPI.getUTF8Text();

TextView textView = findViewById(R.id.textView);
textView.setText(recognizedText);

Clean up:

To release resources and clean up, add the following code snippet to your main activity's onDestroy method:

if (tessBaseAPI != null) {
    tessBaseAPI.end();
}
