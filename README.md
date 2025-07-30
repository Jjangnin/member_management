# 📄 EV Insight Portal - 전기차 판매량 예측 시스템

> **전기차(EV) 판매 데이터를 기반으로 미래 판매량을 예측하는 웹 플랫폼**

---

## 📚 목차

1. [프로젝트 개요](#-프로젝트-개요)  
2. [프로젝트 목적](#-프로젝트-수행-목적)  
3. [기술 스택](#-기술-스택)  
4. [주요 기능 소개](#-주요-기능-소개)  
5. [데이터 출처 및 구성](#-데이터-출처-및-구성)  
6. [데모 영상](#-데모-영상)  

---

## 📌 프로젝트 개요

**EV Insight Portal**은 전기차 월별 판매 데이터를 분석하여  
**랜덤 포레스트(Random Forest)** 및 **서포트 벡터 회귀(SVR)** 모델을 통해  
미래 판매량을 예측하는 기능을 제공하는 웹 기반 예측 시스템입니다.

- 회원 로그인/관리 기능 (PHP + MySQL)  
- CSV 기반의 EV 판매 데이터 업로드 및 시각화  
- Python을 통한 머신러닝 예측 결과 및 차트 시각화 출력

---

## 🎯 프로젝트 수행 목적

- 전기차 보급 추세에 맞춰 월별 판매량을 예측하여 인사이트 도출  
- 실시간 예측 결과를 웹 UI에 통합하는 풀스택 구현 경험  
- Python + PHP + MySQL 연동 및 통합 구현 실습

---

## 🛠 기술 스택

| 항목              | 사용 기술                          |
|-------------------|-----------------------------------|
| Backend           | PHP, MySQL                        |
| Frontend          | HTML, CSS, JavaScript             |
| 데이터 분석       | Python (Pandas, Scikit-learn, Matplotlib) |
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
- Random Forest Regressor + 하이퍼파라미터 튜닝  
- Support Vector Regressor (SVR) + GridSearchCV  
- 예측 결과를 `predicted_sales.csv`로 저장

### 4. 시각화 기능
- **전체 EV 판매량**에 대한 **꺾은선 그래프**  
- **차종별 판매량 비교**를 위한 **막대그래프** 제공  
- 결과는 `EV_F.html`을 통해 웹에서 시각적으로 확인 가능

---

## 📂 데이터 출처 및 구성

### 📌 데이터 출처
- 본 프로젝트는 Hugging Face에서 공개된 전기차 판매량 데이터셋을 활용하였습니다.  
- 📊 [EV Sales Dataset on Hugging Face](https://huggingface.co/datasets/Akshat1509/Electric_Vehicle_Sales_India)

### 🧾 데이터셋 정보

- 총 **96,846개의 행**과 다음과 같은 **8개 컬럼**으로 구성되어 있습니다:

| 컬럼명              | 설명 |
|---------------------|------|
| `Year`              | 연도 (예: 2014.0) |
| `Month_Name`        | 월 이름 (예: jan, feb 등) |
| `Date`              | 등록일자 (`dd/mm/yyyy` 형식) |
| `State`             | 등록된 인도 지역 (주 단위) |
| `Vehicle_Class`     | 차량의 등록 유형 (예: BUS, AMBULANCE 등) |
| `Vehicle_Category`  | 대분류 (예: 2-Wheelers, 4-Wheelers 등) |
| `Vehicle_Type`      | 세부 분류 (예: 2W_Personal, 4W_Shared 등) |
| `EV_Sales_Quantity` | 해당 조건에서의 전기차 등록 대수 |

> 머신러닝 학습에서는 연도(`Year`)와 월(`Month`)을 주요 특성으로 사용하며, 월별 판매량 합계(`EV_Sales_Quantity`)를 예측 대상으로 설정합니다.

---

## 🎥 데모 영상

아래 링크에서 **EV Insight Portal**의 실제 동작을 확인하실 수 있습니다:

[▶️ Watch Demo Video on Google Drive](https://drive.google.com/file/d/1QzFV1OAHr98qfs1f7CX0JO74tA6APw06/view?usp=sharing)
