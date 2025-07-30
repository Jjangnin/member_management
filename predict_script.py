import pandas as pd
import numpy as np
from sklearn.model_selection import train_test_split, GridSearchCV
from sklearn.ensemble import RandomForestRegressor
from sklearn.svm import SVR
from sklearn.metrics import mean_squared_error

# 데이터 파일 경로 설정
file_path = 'C://xampp//htdocs//myproject//EV_Dataset.csv'

# 데이터 읽기
data = pd.read_csv(file_path)

# Month_Name을 숫자로 변환하기 위한 딕셔너리 생성
month_mapping = {
    'jan': 1, 'feb': 2, 'mar': 3, 'apr': 4, 'may': 5, 'jun': 6,
    'jul': 7, 'aug': 8, 'sep': 9, 'oct': 10, 'nov': 11, 'dec': 12
}

# Month_Name을 숫자로 변환
data['Month'] = data['Month_Name'].map(month_mapping)

# 연도와 월로 새로운 날짜 생성
data['Date'] = pd.to_datetime(data[['Year', 'Month']].assign(DAY=1))

# 월별 전기차 판매량 집계
monthly_sales = data.groupby('Date')['EV_Sales_Quantity'].sum().reset_index()

# 특성과 목표 변수 정의
monthly_sales['Month'] = monthly_sales['Date'].dt.month
monthly_sales['Year'] = monthly_sales['Date'].dt.year
X = monthly_sales[['Year', 'Month']]  # 특성
y = monthly_sales['EV_Sales_Quantity']  # 목표 변수

# 데이터 분할 (훈련 데이터와 테스트 데이터로 분할)
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 랜덤 포레스트 회귀 모델 생성 및 하이퍼파라미터 튜닝
rf_model = RandomForestRegressor(random_state=42)

# 하이퍼파라미터 그리드 설정
rf_param_grid = {
    'n_estimators': [50, 100, 200],
    'max_depth': [None, 10, 20, 30],
    'min_samples_split': [2, 5, 10]
}

# 그리드 서치 설정
rf_grid_search = GridSearchCV(estimator=rf_model, param_grid=rf_param_grid, 
                               scoring='neg_mean_squared_error', cv=5, verbose=1)
rf_grid_search.fit(X_train, y_train)

# 최적 모델 및 성능 평가
best_rf_model = rf_grid_search.best_estimator_
y_pred_rf = best_rf_model.predict(X_test)
rf_mse = mean_squared_error(y_test, y_pred_rf)
print(f'Random Forest Mean Squared Error: {rf_mse:.2f}')

# 서포트 벡터 회귀 모델 생성 및 하이퍼파라미터 튜닝
svr_model = SVR()

# 하이퍼파라미터 그리드 설정
svr_param_grid = {
    'C': [0.1, 1, 10, 100],
    'epsilon': [0.1, 0.2, 0.5],
    'kernel': ['linear', 'rbf']
}

# 그리드 서치 설정
svr_grid_search = GridSearchCV(estimator=svr_model, param_grid=svr_param_grid, 
                                scoring='neg_mean_squared_error', cv=5, verbose=1)
svr_grid_search.fit(X_train, y_train)

# 최적 모델 및 성능 평가
best_svr_model = svr_grid_search.best_estimator_
y_pred_svr = best_svr_model.predict(X_test)
svr_mse = mean_squared_error(y_test, y_pred_svr)
print(f'SVR Mean Squared Error: {svr_mse:.2f}')

# 결과를 CSV 파일로 저장
results_df = pd.DataFrame({
    'Actual Sales': y_test,
    'Random Forest Predictions': y_pred_rf,
    'SVR Predictions': y_pred_svr
})

results_df.to_csv('C://xampp//htdocs//myproject//predicted_sales.csv', index=False)

