---
marp: true
size: 4:3
---

# Project 1 Plan

**Submission Deadline:** [Write down the deadline]

Planning anchors: HW2 is due in Week 4, Project 1 starts in Week 5, HW3 Q&A
is in Week 7, Midterm 1 is in Week 8, and Project 1 is due in Week 9.
Canvas is the official source for exact deadlines.

---

## Your Project 1 Submission Date

1. **When (Plan): October 16th end of week 8**
2. **When (Actual): October 16th**   
3. **Comment:**

---

## Planned API Endpoints

List the 10 REST API endpoints you plan to implement for Project 1. Mark
Bearer-token-protected endpoints with `*` (at least 2 required). Leave
"Implemented On" blank for now — in Project 1 you will fill in the actual
date you finished each endpoint, as proof that your implementation followed
your plan.

Example format:

```txt
* means Bearer token required
GET /students → Get all students
GET /students/1 → Get student by ID
POST /students → Create new student
PUT /students/1 → Update student
DELETE /students/1 → Delete student *
```

| # | Endpoint | Description | Bearer | Implemented On |
|---|----------|--------------|:---:|---|
| 1 | GET /car | Gets all cars |  |  |
| 2 | Get /car/{plate} | gets car by plate number |  |  |
| 3 | Delete /car/{plate}  | deletes a car by its plate | * |  |
| 4 | POST /car| adds new car |  |  |
| 5 | PUT /car/{plate, color} | updates car color and plate no. |  |  |
| 6 | GET /lot | gets all parking lots |  |  |
| 7 | GET /lot/type | gets parking lot by their type |  |  |
| 8 | POST /lot | adds new parking lot |  |  |
| 9 | Put /car/{make, lot} | moves brand to different lot |  |  |
| 10 | Delete /lot | deletes lot and associated cars | * |  |

---

## Project 1 Milestones

### Milestone 1: API and Database Design

1. **What:** Finalize all 10 endpoints planned in HW2 (2 of them Bearer-token protected); design the MySQL table(s).
2. **When (Plan): end of week 5**  
3. **When (Actual): end of week 6** 
4. **Comment:** some of my API endpoints changed.

---

### Milestone 2: PHP/MySQL Implementation and Tests

1. **What:** Implement CRUD endpoints and create cURL plus HTML/JavaScript tests.
2. **When (Plan): end of week 6**  
3. **When (Actual): 7**  
4. **Comment:** used the index.php from week6 Rest API and other bits of code from that week to start my design.


---

### Milestone 3: Tutorial and NGINX Deployment

1. **What:** Complete tutorial slides, test NGINX deployment, and organize `code/`, `presentation/`, and `plan/`.
2. **When (Plan): end of week 8**  
3. **When (Actual): week 9**
4. **Comment:** took forever troubleshooting ended up having to delete NGINX and all its associated files then reinstall them to get it to work right. 

---
