# Student Management - PHP + MySQL + Kubernetes

## Project Components

- PHP + HTML + CSS Student Management application
- Docker image
- MySQL StatefulSet
- PersistentVolumeClaim through volumeClaimTemplates
- Kubernetes Secret
- Kubernetes ConfigMap
- PHP Deployment
- MySQL Service
- PHP NodePort Service
- NetworkPolicy

## Application Features

- Add student
- View students
- Delete student
- MySQL persistent storage

## Folder Structure

student-management/
├── app/
│   ├── index.php
│   ├── style.css
│   └── Dockerfile
├── kubernetes/
│   └── student-management.yaml
└── README.md

## Minikube Deployment

Build the image:

docker build -t student-app:1.0 ./app

Load it into Minikube:

minikube image load student-app:1.0

Apply Kubernetes resources:

kubectl apply -f kubernetes/student-management.yaml

Check resources:

kubectl get pods
kubectl get svc
kubectl get statefulset
kubectl get deployment
kubectl get pvc
kubectl get networkpolicy

Open the application:

minikube service student-app

## MySQL Test

Check MySQL:

kubectl get pod mysql-0

Enter MySQL:

kubectl exec -it mysql-0 -- mysql -u studentuser -p studentdb

Password:

studentpassword

Then:

SHOW TABLES;
SELECT * FROM students;

Exit:

exit

## Architecture

Browser
  |
  v
PHP NodePort Service
  |
  v
PHP Deployment
  |
  | TCP 3306
  v
NetworkPolicy
  |
  v
MySQL Service
  |
  v
MySQL StatefulSet
  |
  v
PVC
  |
  v
Persistent Storage

## ArgoCD Deployment (GitOps)

Apply ArgoCD Application manifest:

```bash
kubectl apply -f argocd-application.yaml -n argocd
```

Verify ArgoCD Application status:

```bash
kubectl get application student-management -n argocd
```

Check deployed application resources in `sp` namespace:

```bash
kubectl get all -n sp
```

