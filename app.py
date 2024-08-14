from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from ultralytics import YOLO
import json

app = FastAPI()

app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://localhost:3000"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

model = YOLO("best (1).pt")

@app.get("/predict")
async def predict():
    # Make prediction
    results = model.predict('./Pictures/test3.jpg')
    
    # Process results
    class_list = [int(c) for x1, y1, x2, y2, prob, c in results[0].boxes.data.numpy()]
    class_dict = {}
    for c in class_list:
        class_name = results[0].names[c]
        if class_name in class_dict:
            class_dict[class_name] += 1
        else:
            class_dict[class_name] = 1
    
    return class_dict

if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)