export type DataRecord = { key: string; value: number }

export const data: DataRecord[] = [
  { key: 'male', value: 1396 },
  { key: 'female', value: 928 },
]

export const dataHalf: DataRecord[] = [
  { key: 'male', value: 1000 },
  { key: 'female', value: 508 },
]


// BarChart
const dataDepartments = {
  "AGRICULTURE": [
    {
      department: "CA",
      male: 20,
      female: 10,
    },
    {
      department: "AS",
      male: 15,
      female: 25,
    },
    {
      department: "CS",
      male: 30,
      female: 15,
    }
  ],
  "ARTS AND SCIENCES": [
    {
      department: "HM",
      male: 20,
      female: 10,
    },
    {
      department: "TM",
      male: 15,
      female: 25,
    },
    {
      department: "PolSci",
      male: 30,
      female: 15,
    },
    {
      department: "Accountancy",
      male: 30,
      female: 35,
    }
  ],
}

export type ElectionDatum = {
  department: string;
  male: number;
  female: number;
}

export const colors = {
  female: '#f45a6d',
  male: '#2780eb',
}

export const capitalize = (s: string): string => {
  return s.charAt(0).toUpperCase() + s.slice(1)
}

export const getSelectedDepartment = (s: string) => {
  let arr = dataDepartments[s]
  const res: ElectionDatum[] = arr

  return res
}

export const dataBar: ElectionDatum[] = [
  {
    department: "CA",
    male: 20,
    female: 10,
  },
  {
    department: "AS",
    male: 15,
    female: 25,
  },
  {
    department: "CS",
    male: 30,
    female: 15,
  }
]

