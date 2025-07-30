# 📄 EV Insight Portal - 전기차 판매량 예측 시스템

> **전기차(EV) 판매 데이터를 기반으로 미래 판매량을 예측하는 웹 플랫폼**

---

## 📚 목차

1. [프로젝트 개요](#-프로젝트-개요)
2. [프로젝트 목적](#-프로젝트-수행-목적)
3. [기술 스택](#-기술-스택)
4. [주요 기능 소개](#-주요-기능-소개)
5. [데모 영상](#-데모-영상)
6. [소스코드 링크](#-소스코드-링크)

---

## 📌 프로젝트 개요

**EV Insight Portal**은 전기차 월별 판매 데이터를 분석하여  
**랜덤 포레스트(Random Forest)** 및 **서포트 벡터 회귀(SVR)** 모델을 통해  
미래 판매량을 예측하는 기능을 제공하는 웹 기반 예측 시스템입니다.

- 회원 로그인/관리 기능 (PHP + MySQL)  
- CSV 기반의 EV 판매 데이터 업로드 및 시각화  
- Python을 통한 머신러닝 예측 결과 연동 및 출력

---

## 🎯 프로젝트 수행 목적

- **전기차 보급 추세**에 맞춰 월별 판매량을 예측하여 인사이트 도출  
- 실시간 예측 결과를 **웹 UI에 통합**하는 풀스택 구현 경험  
- Python + PHP + MySQL 연동 및 통합 구현 실습

---

## 🛠 기술 스택

| 항목              | 사용 기술                          |
|-------------------|-----------------------------------|
| Backend           | PHP 7.x, MySQL 8.x                |
| Frontend          | HTML, CSS, JavaScript             |
| 데이터 분석       | Python (Pandas, Sklearn, Matplotlib) |
| 예측 모델링       | Random Forest, SVR (GridSearchCV) |
| 서버 환경         | XAMPP (Apache + MySQL + PHP)      |

---

## 🚀 주요 기능 소개

### 1. 회원가입 및 로그인
- 사용자 등록, 로그인, 로그아웃 기능  
- 관리자 전용 회원 정보 수정/삭제

### 2. 전기차 판매 데이터 업로드 및 처리
- `EV_Dataset.csv` 파일을 읽고 월별 판매량 집계  
- `Year`, `Month`를 특성으로 한 예측 모델 훈련

### 3. 머신러닝 기반 판매 예측
- **Random Forest Regressor** + 하이퍼파라미터 튜닝  
- **Support Vector Regressor (SVR)** + GridSearchCV  
- 예측 결과를 `predicted_sales.csv`로 저장하고 HTML로 시각화

### 4. 결과 시각화 및 리포트
- `EV_F.html` 페이지에 예측 결과 표 형태로 출력  
- 향후: 차트 시각화로 확장 가능

---

## 🎥 데모 영상

아래 링크에서 **EV Insight Portal**의 실제 동작을 확인하실 수 있습니다:

[▶️ Watch Demo Video on Google Drive](https://drive.google.com/file/d/1QzFV1OAHr98qfs1f7CX0JO74tA6APw06/view?usp=sharing)
